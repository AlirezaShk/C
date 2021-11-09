import re
from urllib.parse import unquote
from main.models import *
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException


class UniversityCrawler():
    def __init__(self, id, department, webdriver):
        self.id = id
        self.currentDep = department
        self.webdriver = webdriver

    def eachPersonInfo(self, response):
        name = response.css('h1.campl-sub-title::text').get()
        university = University.objects.get(id=self.id)
        person = Researcher.objects.get(name=name, university=university)
        info = response.css('#block-system-main .group-sd-accordion')
        for i in range(0, len(info.css('.field-group-format-toggler'))):
            title = info.css(
                '.field-group-format-toggler')[i].css('a::text').get()
            text = info.css('.field-group-format-wrapper')[i]
            links = text.css('a')
            if title == 'Biography':
                person.bio = '\n'.join(
                    text.css('.field-item *::text').getall())
                for link in links:
                    label = link.css('::text').get()
                    url = link.css('::attr(href)').get()
                    del_urls = []
                    if 'CV' in label:
                        person.urls.append(
                            {'label': 'CV', 'url': url})
                        del_urls.append(url)
                    elif 'Curriculum Vitae' in label:
                        person.urls.append(
                            {'label': 'CV', 'url': url})
                        del_urls.append(url)
                    elif label == url:
                        del_urls.append(url)
                    elif 'tinyurl' in url:
                        del_urls.append(url)

                    if len(person.bio) > 0:
                        for url in del_urls:
                            parentElem = text.css(
                                'a[href="' + url + '"]').xpath('..')
                            person.bio = person.bio.replace(
                                '\n' + str(
                                    parentElem.css('strong::text').get() or '') + str(parentElem.css('::text').get() or ''), '')
                            person.bio = person.bio.replace(
                                '\n' + url, '')
                            if parentElem.css('::text').get() == None:
                                person.urls.append({'label': parentElem.css(
                                    '*:not(a)::text').get(), 'url': url})
                            else:
                                person.urls.append(
                                    {'label': parentElem.css('::text').get(), 'url': url})
                        person.bio = person.bio.replace('External links:', '')

            elif title == 'Research':
                person.interests = '\n'.join(
                    text.css('.field-item *::text').getall())
                for link in links:
                    label = link.css('::text').get()
                    url = link.css('::attr(href)').get()
                    del_urls = []
                    if 'CV' in label:
                        person.urls.append(
                            {'label': 'CV', 'url': url})
                        del_urls.append(url)
                    elif 'Curriculum Vitae' in label:
                        person.urls.append(
                            {'label': 'CV', 'url': url})
                        del_urls.append(url)
                    elif label == url:
                        del_urls.append(url)
                    elif 'tinyurl' in url:
                        del_urls.append(url)

                    if len(person.interests) > 0:
                        for url in del_urls:
                            parentElem = text.css(
                                'a[href="' + url + '"]').xpath('..')
                            person.interests = person.interests.replace(
                                '\n' + str(
                                    parentElem.css('strong::text').get() or '') + str(parentElem.css('::text').get() or ''), '')
                            person.interests = person.interests.replace(
                                '\n' + url, '')
                            if parentElem.css('::text').get() == None:
                                person.urls.append({'label': parentElem.css(
                                    '*:not(a)::text').get(), 'url': url})
                            else:
                                person.urls.append(
                                    {'label': parentElem.css('::text').get(), 'url': url})
                        person.interests = person.interests.replace(
                            'External links:', '')

                links = text.css('a')
                for link in links:
                    if 'CV' in link.css('::text').get():
                        person.urls.append(
                            {'label': 'CV', 'url': link.css('::attr(href)').get()})
                    elif 'Curriculum Vitae' in link.css('::text').get():
                        person.urls.append(
                            {'label': 'CV', 'url': link.css('::attr(href)').get()})

            elif title == 'Publications':
                links = text.css('.external-link::attr(href)').getall()
                for link in links:
                    person.urls.append({'label': 'Publications', 'url': link})

        contactInfo = response.css(
            '#block-ds-extras-sd-contact .field .field-item')
        person.contactInfo = ''
        for cinfo in contactInfo:
            if cinfo.css('a::attr(href)').get() == None:
                person.contactInfo += cinfo.css('::text').get() + '\n'
            else:
                label = cinfo.css('a::text').get()
                url = cinfo.css('a::attr(href)').get()
                if label == url:
                    person.urls.append({'label': 'Other', 'url': url})
                else:
                    person.urls.append({'label': label, 'url': url})
        person.save()

    def parsePeople(self, response):
        people = response.css(
            '#block-system-main .view .view-content .campl-row')
        urls = []
        field_name = response.css('.campl-sub-title::text').get()
        for person in people:
            name = person.css("div[property='dc:title']")
            mainURL = re.search(
                '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
            person_urls = [{'label': 'University Page',
                            'url': mainURL + "/" + name.css('a::attr(href)').get()}]
            name = name.css('::text').get()
            if 'Emiritus' in name:
                continue
            role = ', '.join(person.css(
                '.field-name-field-sd-job-titles .field-item::text').getall())
            if 'Emiritus' in role:
                continue
            university = University.objects.get(id=self.id)
            if university.logo == None:
                university.logo = mainURL + response.css(
                    '.campl-main-logo img::attr(src)').get()
                university.color = 'cambridge-orange'
                university.save()
            email = person.css(
                '.field-name-field-sd-email-address a::attr(href)').get()
            hindex = ''
            try:
                person = Researcher.objects.get(
                    name=name, university=university)
                print('********* WARNING *********')
                print(person_urls[0]['url'])
            except Researcher.DoesNotExist:
                urls.append(person_urls[0]['url'])
                person = Researcher.objects.create(
                    name=name,
                    urls=person_urls,
                    university=university,
                    email=email,
                    fields={self.currentDep: [field_name]},
                    hindex=hindex,
                    role=role
                )
        return response.follow_all(urls, self.eachPersonInfo)
