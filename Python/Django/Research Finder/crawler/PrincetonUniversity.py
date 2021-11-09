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
        # print(url)
        # print({"label": "University Page", "url": url})
        person = Researcher.objects.get(
            urls__contains={"label": "University Page", "url": url},
            university=university
        )
        content = response.css("#content")
        person.name = content.css('#page-title::text').get()
        contactInfo = content.css("#people-contact-info")
        person.contactInfo = ''
        for field in contactInfo.css(".field"):
            classList = field.xpath("@class").get()
            # is role?
            if 'field-name-field-people-position' in classList:
                person.role = ', '.join(
                    field.css(".field-item::text").extract()
                )
            elif 'field-name-field-people-phone' in classList:
                person.contactInfo += 'Phone: ' + ', '.join(
                    field.css(".field-item::text").extract()
                ) + '\n'
            elif 'field-name-field-people-email' in classList:
                person.email += field.css("a::attr(href)").get()
            elif 'field-name-field-people-office-location' in classList:
                person.contactInfo += 'Office Location: ' + ', '.join(
                    field.css(".field-item::text").extract()
                ) + '\n'
            elif 'field-name-field-people-website' in classList:
                urls = field.css(".field-item a")
                for url in urls:
                    person.urls.append({
                        'label': url.css('::text').get(),
                        'url': url.css('::attr(href)').get(),
                    })
            elif 'field-name-field-advisees' in classList:
                relatedPeople = field.css(".field-item .view-content li")
                for relPerson in relatedPeople:
                    person.relatedPeople.append({
                        'title': 'Advisee',
                        'name': relPerson.css('a::text').get(),
                        'url': relPerson.css('a::attr(href)').get()
                    })
            elif 'field-name-field-people-assistant' in classList:
                relatedPeople = field.css(".field-item .view-content li")
                for relPerson in relatedPeople:
                    person.relatedPeople.append({
                        'title': 'Assistant',
                        'name': relPerson.css('a::text').get(),
                        'url': relPerson.css('a::attr(href)').get()
                    })
            else:
                print("-------------- NEW FIELD --------------")
                print(classList)
                print("\n")
        info = content.css('#people-detail-info')
        person.interests = None
        person.bio = ''
        bio = info.css('.field-name-body .field-item *')
        for item in bio:
            text = ', '.join(item.css("::text").extract()).strip()
            if any([
                text == ' , ',
                text == ', ',
                text == ' ',
                text == '\n',
                len(text) == 1,
                len(text) == 0
            ]):
                continue
            if item.xpath('name()').get() == 'sup':
                if person.bio[-2:] == ', ':
                    text = person.bio[:-2]
                else:
                    text = person.bio
                person.bio = text + '^' + item.css('::text').get()
                continue
            elif item.xpath('name()').get() == 'sub':
                if person.bio[-2:] == ', ':
                    text = person.bio[:-2]
                else:
                    text = person.bio
                person.bio = text + '_' + item.css('::text').get()
                continue
            if 'Selected Publications' == text:
                break
            href = item.css("::attr(href)").get()
            if href is not None:
                person.urls.append({
                    'label': text,
                    'url': href
                })
                continue
            if text in person.bio:
                person.bio = person.bio.replace(text, text + '\n')
            else:
                person.bio += text + '\n'
        person.bio = None if person.bio == '' else person.bio
        # urls = header.css('.page-header-bottom-overlay .link-arrow')
        # for url in urls:
        #     person.urls.append(
        #         {'label': 'Other', 'url': url.css("::attr(href)").get()})
        # urls = info.css('.field--name-field-stripes-narrow a')
        # for url in urls:
        #     person.urls.append(
        #         {'label': url.css('::text').get(), 'url': url.css("::attr(href)").get()})
        person.save()

    def parsePeople(self, response):
        people = response.css(
            '#content .container-fluid .pane-people-grid-people-grid-view')[0].css(
            '.people-grid-person-info')
        field = response.css('#page-title::text').get()
        urls = []
        blacklist = ['Emeritus', 'emeritus']
        for person in people:
            role = ', '.join(person.css(
                '.people-grid-position li::text').extract())
            if any(bl in role for bl in blacklist):
                continue
            name = person.css('.people-grid-name-linked a::text').get()
            mainURL = re.search(
                '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
            university = University.objects.get(id=self.id)
            if university.logo is None:
                university.logo = "https://www.princeton.edu/themes/custom/tony/logo.svg"
                university.color = 'princeton-dark-teal'
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
                Researcher.objects.create(
                    name=name,
                    email="<Requires Update>",
                    urls=[{"label": "University Page", "url": mainURL + person.css(
                        '.people-grid-name-linked a::attr(href)').get()}],
                    university=university,
                    hindex=hindex,
                    fields={self.currentDep: [field]},
                    role=role
                )
            except django.db.utils.IntegrityError as e:
                Researcher.objects.create(
                    name=name,
                    email="<Requires Update>",
                    urls=[{"label": "University Page", "url": mainURL + person.css(
                        '.people-grid-name-linked a::attr(href)').get()}],
                    university=university,
                    hindex=hindex,
                    fields={self.currentDep: [field]},
                    role=role
                )
            urls.append(mainURL + person.css(
                        '.people-grid-name-linked a::attr(href)').get())
        return response.follow_all(urls, self.eachPersonInfo)
