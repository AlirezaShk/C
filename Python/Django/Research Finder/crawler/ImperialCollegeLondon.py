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
            # 'Centre for Processable Electronics': self.type5,
            # 'Centre for Processable Electronics': self.type5,
        }

    def eachPersonInfo(self, response):
        # url = response.request.url
        # university = University.objects.get(id=self.id)
        # # print(url)
        # # print({"label": "University Page", "url": url})
        # person = Researcher.objects.get(
        #     urls__contains={"label": "University Page", "url": url},
        #     university=university
        # )
        # content = response.css("#content")
        # person.name = content.css('#page-title::text').get()
        # contactInfo = content.css("#people-contact-info")
        # person.contactInfo = ''
        # for field in contactInfo.css(".field"):
        #     classList = field.xpath("@class").get()
        #     # is role?
        #     if 'field-name-field-people-position' in classList:
        #         person.role = ', '.join(
        #             field.css(".field-item::text").extract()
        #         )
        #     elif 'field-name-field-people-phone' in classList:
        #         person.contactInfo += 'Phone: ' + ', '.join(
        #             field.css(".field-item::text").extract()
        #         ) + '\n'
        #     elif 'field-name-field-people-email' in classList:
        #         person.email += field.css("a::attr(href)").get()
        #     elif 'field-name-field-people-office-location' in classList:
        #         person.contactInfo += 'Office Location: ' + ', '.join(
        #             field.css(".field-item::text").extract()
        #         ) + '\n'
        #     elif 'field-name-field-people-website' in classList:
        #         urls = field.css(".field-item a")
        #         for url in urls:
        #             person.urls.append({
        #                 'label': url.css('::text').get(),
        #                 'url': url.css('::attr(href)').get(),
        #             })
        #     elif 'field-name-field-advisees' in classList:
        #         relatedPeople = field.css(".field-item .view-content li")
        #         for relPerson in relatedPeople:
        #             person.relatedPeople.append({
        #                 'title': 'Advisee',
        #                 'name': relPerson.css('a::text').get(),
        #                 'url': relPerson.css('a::attr(href)').get()
        #             })
        #     elif 'field-name-field-people-assistant' in classList:
        #         relatedPeople = field.css(".field-item .view-content li")
        #         for relPerson in relatedPeople:
        #             person.relatedPeople.append({
        #                 'title': 'Assistant',
        #                 'name': relPerson.css('a::text').get(),
        #                 'url': relPerson.css('a::attr(href)').get()
        #             })
        #     else:
        #         print("-------------- NEW FIELD --------------")
        #         print(classList)
        #         print("\n")
        # info = content.css('#people-detail-info')
        # person.interests = None
        # person.bio = ''
        # bio = info.css('.field-name-body .field-item *')
        # for item in bio:
        #     text = ', '.join(item.css("::text").extract()).strip()
        #     if any([
        #         text == ' , ',
        #         text == ', ',
        #         text == ' ',
        #         text == '\n',
        #         len(text) == 1,
        #         len(text) == 0
        #     ]):
        #         continue
        #     if item.xpath('name()').get() == 'sup':
        #         if person.bio[-2:] == ', ':
        #             text = person.bio[:-2]
        #         else:
        #             text = person.bio
        #         person.bio = text + '^' + item.css('::text').get()
        #         continue
        #     elif item.xpath('name()').get() == 'sub':
        #         if person.bio[-2:] == ', ':
        #             text = person.bio[:-2]
        #         else:
        #             text = person.bio
        #         person.bio = text + '_' + item.css('::text').get()
        #         continue
        #     if 'Selected Publications' == text:
        #         break
        #     href = item.css("::attr(href)").get()
        #     if href is not None:
        #         person.urls.append({
        #             'label': text,
        #             'url': href
        #         })
        #         continue
        #     if text in person.bio:
        #         person.bio = person.bio.replace(text, text + '\n')
        #     else:
        #         person.bio += text + '\n'
        # person.bio = None if person.bio == '' else person.bio
        # urls = header.css('.page-header-bottom-overlay .link-arrow')
        # for url in urls:
        #     person.urls.append(
        #         {'label': 'Other', 'url': url.css("::attr(href)").get()})
        # urls = info.css('.field--name-field-stripes-narrow a')
        # for url in urls:
        #     person.urls.append(
        #         {'label': url.css('::text').get(), 'url': url.css("::attr(href)").get()})
        # person.save()
        pass

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
        # try:
        #     return self.parser[field](response)
        # except:
        #     print(field)

        # urls = []
        # blacklist = ['Emeritus', 'emeritus']
        # for person in people:
        #     # role = ', '.join(person.css(
        #     #     '.people-grid-position li::text').extract())
        #     # if any(bl in role for bl in blacklist):
        #     #     continue
        #     role = '<Requires Update>'
        #     name = person.css('::text').get().split(', ')
        #     try:
        #         name = name[1] + ' ' + name[0]
        #     except:
        #         print(name)
        #         continue
        #     mainURL = re.search(
        #         '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
        #     url = person.css('::attr(href)').get()
        #     if "person=" in url:
        #         urlName = url.split('person=')[1]
        #         url = mainURL + '/people/' + urlName
        #     university = University.objects.get(id=self.id)
        #     if university.logo is None:
        #         university.logo = "https://www.imperial.ac.uk/assets/website/images/favicon/favicon-144.png"
        #         university.color = 'ICL-blue'
        #         university.save()
        #     try:
        #         person = Researcher.objects.get(
        #             name=name,
        #             university=university
        #         )
        #         person.fields[self.currentDep].append(field)
        #         person.save()
        #         continue
        #     except Researcher.DoesNotExist:
        #         pass
        #     hindex = ''

        #     Researcher.objects.create(
        #         name=name,
        #         email="<Requires Update>",
        #         urls=[{"label": "University Page", "url": mainURL + person.css(
        #             '.people-grid-name-linked a::attr(href)').get()}],
        #         university=university,
        #         hindex=hindex,
        #         fields={self.currentDep: [field]},
        #         role=role
        #     )
        #     urls.append(mainURL + person.css(
        #                 '.people-grid-name-linked a::attr(href)').get())
        # return response.follow_all(urls, self.eachPersonInfo)

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
