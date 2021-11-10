import requests
import django
import re
from django.conf import settings
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
        name = response.css(
            '#mastheadContainer .nameAndTitle h1::text').getall()[0]
        university = University.objects.get(name='Stanford University')
        person = Researcher.objects.get(name=name, university=university)
        person.interests = '\n'.join(response.css(
            '#currentResearchAndScholarlyInterestsContent p::text').extract())
        person.bio = '\n'.join(response.css(
            '#bioContent p::text').extract())
        person.contactInfo = '\n'.join(response.css('#contactInfoContent li.primary .contact-info *:not(a)::text').getall())
        relatedPeople = response.css('#contactInfoContent li.alternate .contact-info')
        relPerson = [{
            'title': relatedPeople.css('strong::text').get(),
            'email': relatedPeople.css('.email::attr(href)').get(),
            'label': relatedPeople.css('.name::text').get(),
        }]
        if relatedPeople.css('.title::text').get() != None:
            relPerson[0]['title'] = relatedPeople.css('.title::text').get()
        person.relatedPeople = relPerson
        if len(str(person.bio or '').replace('\n', '').strip()) in [0, 1]:
            person.bio = None
        if len(str(person.interests or '').replace('\n', '').strip()) in [0, 1]:
            person.interests = None
        person.save()

    def parsePeople(self, response):
        people = response.css(
            '.view-stanford-person .view-content .views-row')
        urls = []
        for person in people:
            role = person.css('.faculty-type::text').get()
            if 'Emeritus' in role:
                continue
            email = person.css('.postcard-col2 a[href*=mailto]::text').get()
            name = person.css('.postcard-col2 h3 a::text').get()
            mainURL = re.search(
                '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
            university = University.objects.get(id=self.id)
            if university.logo == None:
                university.logo = mainURL + response.css(
                    '#top-logo img::attr(src)').get()
                university.color = 'stanford-red'
                university.save()

            fields = {self.currentDep: person.css(
                '.postcard-col3 a::text').getall()}
            try:
                Researcher.objects.get(name=name, university=university)
                continue
            except Researcher.DoesNotExist:
                pass
            hindex=''

            try:
                Researcher.objects.create(
                    name=name,
                    email=email,
                    urls=[{"label": "University Page", "url": person.css(
                        '.postcard-col2 h3 a::attr(href)').get()}],
                    university=university,
                    hindex=hindex,
                    fields=fields,
                    role=role
                )
            except django.db.utils.IntegrityError as e:
                Researcher.objects.create(
                    name=name,
                    email="<Requires Update>",
                    urls=[{"label": "University Page", "url": person.css(
                        '.postcard-col2 h3 a::attr(href)').get()}],
                    university=university,
                    hindex=hindex,
                    fields=fields,
                    role=role
                )
            urls.append(person.css(
                '.postcard-col2 a[href*=profiles]::attr(href)').get())
        return response.follow_all(urls, self.eachPersonInfo)
