import re
from main.models import *
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC


class UniversityCrawler():
    def __init__(self, id, department, webdriver):
        self.id = id
        self.currentDep = department
        self.webdriver = webdriver

    def eachPersonInfo(self, response):
        person = Researcher.objects.filter(
            urls__contains={'label': 'University Page', 'url': response.request.url}).get()
        person.name = response.css('#content h1.node-title::text').get()
        for link in response.css(
                '#content .node-content .pic-bio a::attr(href)').getall():
            if 'people' in link:
                relInfo = response.css(
                    '#content .pic-bio a[href="' + link + '"]')
                relPerson = {
                    'title': relInfo.xpath(
                        '../strong/text()').get(),
                    'link': relInfo.xpath(
                        '@href').get(),
                    'name': relInfo.xpath(
                        'text()').get()
                }
                mainURL = re.search(
                    '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
                if mainURL in relPerson['link']:
                    pass
                else:
                    relPerson['link'] = mainURL + '/' + relPerson['link']
                try:
                    person.relatedPeople.append(
                        {'title': relPerson['title'], 'url': relPerson['link'], 'label': relPerson['name']})
                except (TypeError, AttributeError):
                    person.relatedPeople = []
                    person.relatedPeople.append(
                        {'title': relPerson['title'], 'url': relPerson['link'], 'label': relPerson['name']})
            else:
                person.urls.append({
                    'label': response.css(
                        '#content .pic-bio a[href="' + link + '"]::text').get(),
                    'url': link})
        for link in response.css(
                '#content .node-content .website-details a::attr(href)').getall():
            person.urls.append({
                'label': response.css(
                    '#content .node-content .website-details a[href="' + link + '"]::text').get(),
                'url': link})
        person.email = response.css(
            '#content .node-content a[href*=mailto]::attr(href)').get()
        person.bio = ""
        for paragraph in response.css('#content .node-content .pic-bio .field-name-body .field-item p::text').getall():
            if '_________________' in paragraph:
                continue
            else:
                person.bio += paragraph
        person.role = ','.join(response.css(
            '#content .field-name-field-professional-title .field-item::text').getall())
        person.contactInfo = '\n'.join(response.css(
            '#content .contact-details .field .field-item::text').extract())
        if len(str(person.bio or '').replace('\n', '').strip()) in [0, 1]:
            person.bio = None
        person.save()

    def parsePeople(self, response):
        fields = response.css(
            '#content-panels article .field-items .field-item p:not(:first-child)')
        urls = []
        for field in fields:
            field_name = field.css('strong::text').get()
            people = field.css('a[href*=people]')
            for person in people:
                name = person.css("::text").get()
                mainURL = re.search(
                    '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
                person_urls = [{'label': 'University Page',
                                'url': mainURL + "/" + person.attrib['href']}]
                university = University.objects.get(id=self.id)
                if university.logo == None:
                    university.logo = response.css(
                        '#branding_header img::attr(src)').get()
                    university.color = 'harvard-red'
                    university.save()
                hindex = ''
                try:
                    person = Researcher.objects.get(
                        name=name, university=university)
                    person.fields[self.currentDep].append(field_name)
                    person.save()
                except Researcher.DoesNotExist:
                    urls.append(person.attrib['href'])
                    person = Researcher.objects.create(
                        name=name,
                        urls=person_urls,
                        university=university,
                        fields={self.currentDep: [field_name]},
                        hindex=hindex,
                    )
        return response.follow_all(urls, self.eachPersonInfo)
