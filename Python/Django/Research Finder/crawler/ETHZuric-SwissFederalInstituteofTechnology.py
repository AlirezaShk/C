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
        person.name = response.css(
            '.page-header-title::text').getall()[0]

        info = response.css('.page-main-area-container .page-content')
        person.interests = '\n'.join(info.css('.field--name-field-research-interests').css(
            '.user-research-interests-item::text').extract())
        bio = info.css('.field--name-field-stripes-narrow .main-container')
        person.bio = ''
        for item in bio:
            title = item.css(
                '.field--name-field-vits-title *::text').get()
            if title is not None:
                title = title.strip()
                if title == 'Biography':
                    title = ''
            else:
                title = ''
            text = ''
            for txt in item.css('.main-content-text *::text').extract():
                if 'http' in txt:
                    continue
                text += txt.strip()
            person.bio += title + '\n' + text + '\n'
        header = response.css('.page-header')
        person.email = header.css(
            '.user-profile-link-email .link-email::attr(href)').get()
        if person.email is None:
            person.email = '<Requires Update>'
        telephone = header.css('.page-header-profile-tel a::text').get()
        officeLocation = header.css(
            '.user-profile-bottom-item-container__location::text').get()
        person.contactInfo = '\n'.join([
            telephone if telephone is not None else '',
            officeLocation if officeLocation != None else '',
        ])
        person.fields[self.currentDep] = header.css(
            '.theme-sub-dept-link-list__item a::text').getall()
        urls = header.css('.page-header-bottom-overlay .link-arrow')
        for url in urls:
            person.urls.append(
                {'label': 'Other', 'url': url.css("::attr(href)").get()})
        urls = info.css('.field--name-field-stripes-narrow a')
        for url in urls:
            person.urls.append(
                {'label': url.css('::text').get(), 'url': url.css("::attr(href)").get()})
        if len(str(person.bio or '').replace('\n', '').strip()) in [0, 1]:
            person.bio = None
        if len(str(person.interests or '').replace('\n', '').strip()) in [0, 1]:
            person.interests = None
        person.save()

    def parsePeople(self, response):
        people = response.css(
            '.view-our-people-content-container article')
        fields = {self.currentDep: [response.css(
            '#views-exposed-form-our-people-our-people .select .select-styled::text').get()]}
        urls = []
        blacklist = ['Emeritus', 'Student', 'Visitor', 'Assistant',
                     'student', 'emeritus', 'visitor', 'assistant']
        for person in people:
            role = person.css(
                '.field--name-field-job-title-output::text').get()
            if any(bl in role for bl in blacklist):
                continue
            name = person.css('.field--name-field-hrf-name::text').get()
            mainURL = re.search(
                '^([https\:\/\/]+[A-z.]+)', response.request.url).group(1)
            university = University.objects.get(id=self.id)
            if university.logo is None:
                university.logo = mainURL + response.css(
                    'header .site-logo img::attr(src)').get()
                university.color = 'oxford-midnight-blue'
                university.save()
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
                    urls=[{"label": "University Page", "url": mainURL + person.css(
                        '.field-group-link::attr(href)').get()}],
                    university=university,
                    hindex=hindex,
                    fields=fields,
                    role=role
                )
            except django.db.utils.IntegrityError as e:
                Researcher.objects.create(
                    name=name,
                    email="<Requires Update>",
                    urls=[{"label": "University Page", "url": mainURL + person.css(
                        '.field-group-link::attr(href)').get()}],
                    university=university,
                    hindex=hindex,
                    fields=fields,
                    role=role
                )
            urls.append(mainURL + person.css(
                        '.field-group-link::attr(href)').get())
        return response.follow_all(urls, self.eachPersonInfo)
