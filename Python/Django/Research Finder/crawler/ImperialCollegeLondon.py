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
        self.parser = {
            'The Centre for Cold Matter': self.type1,
            'Centre for Complexity Science': self.type2,
            'Plasma Physics': self.type3,
            'Centre for Plasmonics and Metamaterials': self.type4,
            'Sustainable Energy': self.type5,
            'Soft Electronics': self.type5,
            'Emerging Technologies': self.type5,
            'Astrophysics': self.type6,
            'Imperial Centre For Quantum Engineering, Science And Technology': self.type7,
            'Quantum Optics and Laser Science (QOLS)': self.type6,
        }

    def eachPersonInfo(self, response):
        url = response.request.url
        university = University.objects.get(id=self.id)
        person = Researcher.objects.get(
            urls__contains={"label": "University Page", "url": url},
            university=university
        )
        contactInfo = response.css('#AcademicDetails .elementWrapper')
        person.email = response.css("#emailuser a::attr(href)").get()
        person.contactInfo = ''
        for cInfo in contactInfo:
            title = cInfo.css('.h3wrapper h3::text').get()
            if title == 'Contact':
                info = cInfo.css('p span')
                for each in info:
                    text = ', '.join(each.css("::text").extract())
                    if '+44' in text:
                        person.contactInfo += text.replace(
                            '+44', 'Telephone: +44') + '\n'
                    elif 'Website' in text:
                        person.urls.append({
                            'label': each.css("a::text").get(),
                            'url': each.css("a::attr(href)").get()
                        })
                    elif 'mail' not in each.css("a::attr(href)").get():
                        print('-------- New Field --------')
                        print(text)
            elif title == 'Assistant':
                relPerson = {'title': 'Assistant', 'name': None}
                info = cInfo.css('p span')
                for each in info:
                    text = ', '.join(each.css("::text").extract())
                    if '+44' in text:
                        relPerson['phone'] = text
                    elif '@' in text:
                        relPerson['email'] = 'mailto' + text
                    elif relPerson['name'] is None:
                        relPerson['name'] = text
                    else:
                        print('-------- New RelatedPerson Field --------')
                        print(each)
                person.relatedPeople = [relPerson]
            elif title == 'Location':
                if person.contactInfo is None:
                    person.contactInfo = ''
                info = cInfo.css('p span')
                for each in info:
                    text = ', '.join(each.css("::text").extract())
                    person.contactInfo = text + '\n'
        person.role = ', '.join(
            response.css('.ac_info header em *::text').extract()
        )
        if "Professor" not in person.role:
            person.role = "Professor, " + person.role
        content = response.css("#customContent p")
        person.interests = ''
        person.bio = ''
        for each in content:
            text = '\n'.join(each.css("::text").extract())
            urls = each.css("a")
            for url in urls:
                person.urls.append({
                    'label': str(url.css('::text').get() or 'Other'),
                    'url': url.css('::attr(href)').get(),
                })
                if url.css('::text').get() is not None:
                    text = text.replace('\n' + url.css('::text').get() + '\n',
                                        url.css('::text').get())
            if "Interests" in ','.join(response.css("#customContent *::text").extract()):
                person.interests += text + '\n'
            else:
                person.bio += text + '\n'
        if len(str(person.bio or '').replace('\n', '').strip()) in [0, 1]:
            person.bio = None
        if len(str(person.interests or '').replace('\n', '').strip()) in [0, 1]:
            person.interests = None
        person.save()

    def parsePeople(self, response):
        # people = response.css(
        #     '#page .module .item')[0].css(
        #     '.item-content a')
        university = University.objects.get(id=self.id)
        if university.logo is None:
            university.logo = "https://www.imperial.ac.uk/assets/website/images/favicon/favicon-144.png"
            university.color = 'ICL-blue'
            university.save()
        field = response.css('#section-title a::text').get()
        if field == "Centre for Processable Electronics":
            field = response.css("#page .page-heading h1::text").get().title()
        elif field.title() == "John Adams Institute At Imperial College":
            field = "Imperial Centre For Quantum Engineering, Science And Technology"
        return self.parser[field](field, university, response)

    def type1(self, field, university, response):
        people = response.css(
            '#page .module .panel-group .item')[0].css(
            '.item-content a')
        urls = []
        for person in people:
            role = '<Requires Update>'
            name = person.css('::text').get().split(', ')
            try:
                name = name[1] + ' ' + name[0]
            except:
                print("ERROR")
                print(name)
                continue
            mainURL = re.search(
                '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
            url = person.css('::attr(href)').get()
            if "person=" in url:
                urlName = url.split('person=')[1]
                url = mainURL + '/people/' + urlName
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

    def type2(self, field, university, response):
        people = response.css(
            '#page .module .row')[0].css(
            'ul li')
        urls = []
        for person in people:
            person = person.css("a")[0]
            role = '<Requires Update>'
            name = person.css('::text').get()
            mainURL = re.search(
                '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
            url = person.css('::attr(href)').get()
            if "person=" in url:
                urlName = url.split('person=')[1]
                url = mainURL + '/people/' + urlName
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
            if name == 'Dr Gunnar Pruessner':
                url = 'https://www.imperial.ac.uk/people/g.pruessner'
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

    def type3(self, field, university, response):
        people = response.css(
            '#page .module .panel-group .item')[0].css(
            '.item-content tbody tr')
        urls = []
        for person in people:
            role = '<Requires Update>'
            name = person.css('td')[0].css('img::attr(alt)').get()
            mainURL = re.search(
                '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
            url = person.css('td')[1].css('a::attr(href)').get()
            if "person=" in url:
                urlName = url.split('person=')[1]
                url = mainURL + '/people/' + urlName
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

            if 'mailto' in url:
                url = '<Requires Update>'

            Researcher.objects.create(
                name=name,
                email="<Requires Update>",
                urls=[{"label": "University Page", "url": url}],
                university=university,
                hindex=hindex,
                fields={self.currentDep: [field]},
                role=role
            )

            if url == '<Requires Update>':
                continue
            urls.append(url)
        return response.follow_all(urls, self.eachPersonInfo)

    def type4(self, field, university, response):
        people = response.css(
            '#page .module .stories-list')[0].css(
            '.item')
        urls = []
        for person in people:
            role = '<Requires Update>'
            name = person.css('h3::text').get()
            mainURL = re.search(
                '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
            url = person.css('a::attr(href)').get()
            if "person=" in url:
                urlName = url.split('person=')[1]
                url = mainURL + '/people/' + urlName
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

            if 'mailto' in url:
                url = '<Requires Update>'

            Researcher.objects.create(
                name=name,
                email="<Requires Update>",
                urls=[{"label": "University Page", "url": url}],
                university=university,
                hindex=hindex,
                fields={self.currentDep: [field]},
                role=role
            )

            if url == '<Requires Update>':
                continue
            urls.append(url)
        return response.follow_all(urls, self.eachPersonInfo)

    def type5(self, field, university, response):
        people = response.css(
            '#page .module .row')[0].css(
            '.col')
        urls = []
        for person in people:
            role = '<Requires Update>'
            name = person.css('a .content h3.title::text').get()
            mainURL = re.search(
                '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
            if name == 'Prof Jenny Nelson':
                url = 'https://www.imperial.ac.uk/people/jenny.nelson'
            elif name == 'Prof James Durrant':
                url = 'https://www.imperial.ac.uk/people/j.durrant'
            elif name == 'Prof Matthew Fuchter':
                url = 'https://www.imperial.ac.uk/people/m.fuchter'
            elif name == 'Prof Ji-Seon Kim':
                url = 'https://www.imperial.ac.uk/people/ji-seon.kim'
            elif name == 'Prof Molly Stevens':
                url = 'https://www.imperial.ac.uk/people/m.stevens'
            else:
                url = person.css('a::attr(href)').get()
            if "person=" in url:
                urlName = url.split('person=')[1]
                url = mainURL + '/people/' + urlName
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

            if 'mailto' in url:
                url = '<Requires Update>'

            Researcher.objects.create(
                name=name,
                email="<Requires Update>",
                urls=[{"label": "University Page", "url": url}],
                university=university,
                hindex=hindex,
                fields={self.currentDep: [field]},
                role=role
            )

            if url == '<Requires Update>':
                continue
            urls.append(url)
        return response.follow_all(urls, self.eachPersonInfo)

    def type6(self, field, university, response):
        people = response.css(
            '#page .module .people.list')[0].css(
            'ul')[0].css(
            'li')
        urls = []
        for person in people:
            if person.css('.sr-only').get() is None:
                continue
            role = person.css('.job-title::text').get()
            if "Professor" not in role:
                continue
            name = person.css('.person-name::text').get()
            mainURL = re.search(
                '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
            url = person.css('.name-link::attr(href)').get()
            if name == 'Prof Andrew Jaffe':
                url = 'https://www.imperial.ac.uk/people/a.jaffe'
            url2 = person.css('.dept-wrapper a::attr(href)').get()
            if "person=" in url:
                urlName = url.split('person=')[1]
                url = mainURL + '/people/' + urlName
            if url2 is not None:
                p_urls = [
                    {
                        'label': "University Page",
                        'url': url,
                    },
                    {
                        'label': "Personal Website",
                        'url': url2,
                    }
                ]
            else:
                p_urls = [
                    {
                        'label': "University Page",
                        'url': url,
                    },
                ]
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

            if 'mailto' in url:
                url = '<Requires Update>'

            Researcher.objects.create(
                name=name,
                email="<Requires Update>",
                urls=p_urls,
                university=university,
                hindex=hindex,
                fields={self.currentDep: [field]},
                role=role
            )

            if url == '<Requires Update>':
                continue
            urls.append(url)
        return response.follow_all(urls, self.eachPersonInfo)

    def type7(self, field, university, response):
        people = response.css(
            '#page .module .stories-list.row')[0].css(
            '.item')
        urls = []
        for person in people:
            role = person.css('.story-title::text').extract()[-1].strip()
            if "Professor" not in role:
                continue
            name = person.css('.story-title strong::text').get()
            mainURL = re.search(
                '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
            url = person.css('a::attr(href)').get()
            if "person=" in url:
                urlName = url.split('person=')[1]
                url = mainURL + '/people/' + urlName
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
