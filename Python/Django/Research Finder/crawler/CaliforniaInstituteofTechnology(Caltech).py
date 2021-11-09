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

    def eachPersonInfo(self, response):
        url = response.request.url
        university = University.objects.get(id=self.id)
        person = Researcher.objects.get(
            urls__contains={"label": "University Page", "url": url}, university=university)
        infoBody = response.css('.person-page__body')
        person.fields[self.currentDep] = infoBody.css(
            '.person-page__body__category-twos a::text').extract()
        infoSide = response.css('.person-page__sidebar')
        contactInfo = infoSide.css('.person-contact-info tbody tr')
        person.contactInfo = ''
        for cInfo in contactInfo:
            label = cInfo.css('td')[0].css('::text').get()
            if label == 'Email':
                person.email = cInfo.css('td')[1].css('a::attr(href)').get()
            else:
                value = cInfo.css('td')[1].css('::text').get()
                person.contactInfo += label + ' ' + value + '\n'
        person.contactInfo = None if person.contactInfo == '' else person.contactInfo
        person.interests = '\n'.join(infoBody.css(
            '.person-page__body__research-summary::text').extract()).replace(
            'Research Interests', '')
        if len(person.interests) == 0:
            person.interests = None
        bio = infoBody.css('.person-page__body__profile .rich-text *')
        person.bio = ''
        urls = []
        for p in bio:
            link = p.css('::attr(href)').get()
            if link is not None:
                urls.append(p)
                continue
            txt = p.css('::text').extract()
            if type(txt) is list:
                final = ''
                for t in txt:
                    final += t.strip()
                txt = final
            else:
                txt = txt.strip()
            b = p.css('b::text').get()
            if b is not None:
                if txt == p.css('b::text').get().strip():
                    continue
            if len(txt) > 0:
                person.bio += txt + '\n'
        person.bio = None if person.bio == '' else person.bio
        for a in urls:
            person.urls.append({
                'label': a.css('::text').get(),
                'url': a.css('::attr(href)').get(),
            })
        urls = infoSide.css('.person-page__sidebar__links a')
        for a in urls:
            person.urls.append({
                'label': a.css('::text').get(),
                'url': a.css('::attr(href)').get(),
            })
        person.save()

    def parsePeople(self, response):
        people = response.css(
            '.person-listing .person-listing__person-row')
        fields = {self.currentDep: ['Physics']}
        urls = []
        blacklist = ['Emeritus', 'emeritus']
        for person in people:
            role = person.css(
                '.person-listing__summary__faculty-title::text').get()
            if any(bl in role for bl in blacklist):
                continue
            name = person.css('.person-listing__summary__title::text').get().strip()
            mainURL = re.search(
                '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
            university = University.objects.get(id=self.id)
            if university.logo is None:
                university.logo = mainURL + response.css(
                    '.header .header__wordmark-link img::attr(src)').get()
                university.color = 'caltech-white'
                university.save()
            url = mainURL + \
                person.css(
                    '.person-listing__person-row__wrapper-link::attr(href)').get()
            # fields = {self.currentDep: person.css(
            #     '.postcard-col3 a::text').getall()}
            try:
                Researcher.objects.get(name=name, university=university)
                continue
            except Researcher.DoesNotExist:
                pass

            # hindex:
            # self.webdriver.get("https://www.google.com/search?q=" +
            #                    name.replace(' ', '+') + "+%40+" +
            #                    university.name.replace(' ', '+') + "+h-index+scholar.google")
            # self.webdriver.find_elements_by_css_selector('#search a')[
            #     0].click()
            # try:
            #     hindex = WebDriverWait(self.webdriver, 10).until(
            #         EC.presence_of_element_located(
            #             (By.XPATH, "//a[text()='h-index']/../../td[2]"))
            #     )
            #     hindex.get_attribute(
            #         'textContent')
            # except TimeoutException:
            #     hindex = '<Requires Update>'
            hindex = ''

            try:
                Researcher.objects.create(
                    name=name,
                    email="<Requires Update>",
                    urls=[{"label": "University Page", "url": url}],
                    university=university,
                    hindex=hindex,
                    fields=fields,
                    role=role
                )
            except django.db.utils.IntegrityError as e:
                Researcher.objects.create(
                    name=name,
                    email="<Requires Update>",
                    urls=[{"label": "University Page", "url": url}],
                    university=university,
                    hindex=hindex,
                    fields=fields,
                    role=role
                )
            urls.append(url)
        return response.follow_all(urls, self.eachPersonInfo)
