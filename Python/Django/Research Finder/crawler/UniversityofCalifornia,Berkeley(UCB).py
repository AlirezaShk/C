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
        name = response.css('.bio-name::text').get()
        university = University.objects.get(
            name='University of California, Berkeley (UCB)')
        person = Researcher.objects.get(name=name, university=university)
        person.role = ', '.join(response.css('.bio-titles *::text').extract())
        person.fields[self.currentDep] = response.css(
            '.bio-researcharea small a::text').extract()
        # person.interests = '\n'.join(response.css(
        #     '#currentResearchAndScholarlyInterestsContent p::text').extract())
        person.bio = '\n'.join(response.css(
            '.bio-body *:not(.section-title)::text').extract())
        person.interests = '\n'.join(response.css(
            '.bio-researchinterests *:not(.section-title)::text').extract())
        if len(person.interests.strip()) == 0:
            person.interests = None
        contactInfo = response.css('.bio-contact div')
        person.contactInfo = ''
        for cInfo in contactInfo:
            text = ' '.join(cInfo.css("*::text").extract())
            if '@' in text:
                person.email = cInfo.css("a::attr(href)").get()
                continue
            if 'Main' in text:
                text = text.replace('Main', 'Telephone')
            person.contactInfo += '\n' + text
        person.contactInfo = None if person.contactInfo == '' else person.contactInfo
        urls = response.css('.bio-links .bio-meta-item')
        for url in urls:
            person.urls.append({
                'label': url.css('::text').get(),
                'url': url.css('::attr(href)').get()
            })
            person.bio = person.bio.replace(url.css('::attr(href)').get(), '')
        person.publications_raw = '\n'.join(response.css(
            '.bio-publications .bio-publications-item *::text').extract())
        # relatedPeople = response.css(
        #     '#contactInfoContent li.alternate .contact-info')
        # relPerson = [{
        #     'title': relatedPeople.css('strong::text').get(),
        #     'email': relatedPeople.css('.email::attr(href)').get(),
        #     'label': relatedPeople.css('.name::text').get(),
        # }]
        # if relatedPeople.css('.title::text').get() != None:
        #     relPerson[0]['title'] = relatedPeople.css('.title::text').get()
        # person.relatedPeople = relPerson
        person.save()

    def parsePeople(self, response):
        people = response.css(
            '.view-ucbp-researchers .view-content li')
        urls = []
        for person in people:
            url = person.css('a')[1].css('::attr(href)').get()
            name = person.css('a')[1].css('::text').get()
            role = '<Requires Update>'
            if '(E)' in name:
                continue
            email = '<Requires Update>'
            mainURL = re.search(
                '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
            university = University.objects.get(id=self.id)
            if (university.logo is None) or (university.logo != 'https://www.berkeley.edu/images/uploads/logo-ucberkeley-white.png'):
                    # university.logo = mainURL + response.css(
                    #     'header .logo.navbar-btn img::attr(srcset)').get()
                university.logo = 'https://www.berkeley.edu/images/uploads/logo-ucberkeley-white.png'
                university.color = 'berkeley-white'
                university.save()

            fields = {self.currentDep: '<Requires Update>'}
            try:
                Researcher.objects.get(name=name, university=university)
                continue
            except Researcher.DoesNotExist:
                pass
            hindex = ''

            try:
                Researcher.objects.create(
                    name=name,
                    email=email,
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
