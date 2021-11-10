import requests
import django
import re
from django.conf import settings
from main.models import *


class UniversityCrawler():
    def __init__(self, id, department, webdriver):
        self.id = id
        self.currentDep = department
        self.webdriver = webdriver
        self.urlComplement = {
            'Physics': '/phyen'
        }

    def eachPersonInfo(self, response):
        url = response.request.url
        university = University.objects.get(id=self.id)
        person = Researcher.objects.get(
            urls__contains={"label": "University Page", "url": url},
            university=university
        )
        content = response.css("#vsb_content tbody tr")
        special = False
        cInfo = content[0].css('tbody tr')
        if len(cInfo) > 0:
            cInfo = cInfo[0].css('p')
        else:
            cInfo = content[0].css('p')
        if cInfo is None:
            special = True
            cInfo = content[0].css('tbody tr')[0].css(
                'td::text').get().split('&nbsp;')
        contactFlags = ['Phone', 'Fax', 'China', 'Building']
        person.contactInfo = ''
        # urls = []
        for info in cInfo:
            if not special:
                text = info.css("::text").get()
            else:
                text = info
            if text is None:
                continue
            if person.name.lower() in text.lower():
                person.role = text.lower().replace(
                    person.name.lower(), '').strip().title()
            elif 'Professor' in text:
                person.role = text.strip()
            elif any(flag in text for flag in contactFlags):
                person.contactInfo += text + '\n'
            elif '@' in text:
                person.email = 'mailto:' + text
            elif 'Links' in text:
                # print(person.name)
                # urls.append({'label': text})
                pass
        i = 0
        arr = content.css('td')
        flags = ['Publications', 'Awards']
        for each in arr:
            each = each.css("::text").get()
            # print(each)
            if each is None:
                i += 2
            else:
                i += 1
            if each in ['Interests', 'Research Areas']:
                person.interests = '\n'.join(
                    content[i].css("::text").extract())
                if any(flag in person.interests for flag in flags):
                    person.interests = '\n'.join(
                        content[i - 1].css("::text").extract())
                break
        if len(str(person.interests or '').replace('\n', '').strip()) in [0, 1]:
            person.interests = None
        person.save()

    def parsePeople(self, response):
        fields = response.css(".container .pull-right div")[1:]
        urls = []
        for eachField in fields:
            people = eachField.css(
                '.teacher-list li.fadeInUp')
            field = eachField.css('.lmmc_index_l::text').get()
            for person in people:
                role = '<Requires Update>'
                name = person.css('a::text').get()
                url = person.css('a::attr(href)').get()
                if 'javascript:' in url:
                    continue
                mainURL = re.search(
                    '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
                university = University.objects.get(id=self.id)
                if university.logo is None:
                    university.logo = "https://www.phys.tsinghua.edu.cn/phyen/fonts/1211.png"
                    university.color = 'Tsinghua-red'
                    university.save()
                try:
                    person = Researcher.objects.get(
                        name=name,
                        university=university
                    )
                    person.fields[self.currentDep].append(field)
                    person.save()
                    continue
                except Researcher.DoesNotExist:
                    pass
                hindex = ''
                try:
                    url = url.replace(
                        '..', mainURL + self.urlComplement[self.currentDep])
                except KeyError:
                    raise KeyError(
                        'Define a new key for this department in self.urlComplement property')
                # if url != 'https://www.phys.tsinghua.edu.cn/phyen/info/1071/1243.htm':
                #     continue
                try:
                    Researcher.objects.create(
                        name=name,
                        email="<Requires Update>",
                        urls=[{"label": "University Page", "url": url}],
                        university=university,
                        hindex=hindex,
                        fields={self.currentDep: [field]},
                        role=role
                    )
                except django.db.utils.IntegrityError as e:
                    Researcher.objects.create(
                        name=name,
                        email="<Requires Update>",
                        urls=[{"label": "University Page", "url": url}],
                        university=university,
                        hindex=hindex,
                        fields={self.currentDep: [field]},
                        role=role
                    )
                urls.append(url)
        return response.follow_all(urls, self.eachPersonInfo)
