from main.models import *


class UniversityCrawler():
    def __init__(self, id, department, webdriver):
        self.id = id
        self.currentDep = department
        self.webdriver = webdriver

    def eachPersonInfo(self, response):
        role = ', '.join(response.css(
            "#content .faculty__title::text").extract())
        person = Researcher.objects.filter(
            urls__contains={'label': 'University Page', 'url': response.request.url}).get()
        person.role = response.css(
            '#content .faculty__header .faculty__title::text').get()
        if 'Emeritus' in person.role:
            person.delete()
            return
        header_info = response.css(
            '#content .faculty__header .faculty__header-sidebar-content')
        person.email = header_info.css(
            '.faculty__email a::attr(href)')
        headings = response.css(
            '#content .post-type-faculty .wp-block-opus-core-block-rewrite-oh')
        content = response.css(
            '#content .post-type-faculty .wp-block-opus-core-block-rewrite-op')
        elems = response.css(
            '#content .post-type-faculty .gutenberg-body__content > *:not(figure)')
        person.interests = ''
        person.bio = ''
        more_urls = []
        for elem in elems:
            try:
                if 'wp-block-opus-core-block-rewrite-oh' in elem.xpath(
                        '@class').get():
                    if elem.css(
                            '::text').get().strip() == 'Research Interests':
                        contentType = 'interests'
                    elif elem.css('::text').get() in [
                        'Biographic Sketch',
                        'Biographical Sketch',
                    ]:
                        contentType = 'bio'
                elif 'wp-block-opus-core-block-rewrite-op' in elem.xpath(
                        '@class').get():
                    txt = elem.css(
                        '::text').extract()
                    if txt[0][-1] == ':':
                        continue
                    if contentType == 'interests':
                        person.interests += '\n'.join(txt)
                    elif contentType == 'bio':
                        person.bio += '\n'.join(txt)

                    if len(elem.css('a')) > 0:
                        more_urls.append(elem.css('a'))
                else:
                    more_urls.append(elem.css('a'))
            except:
                print('--------------- DEBUG ---------------')
                print(person.urls[0]['url'])
                print(elem.css('::text').get())
        if person.bio == '':
            person.bio = None
        if person.interests == '':
            person.interests = None
        for link_array in more_urls:
            for link in link_array:
                label = link.css('::text').get()
                url = link.css('::attr(href)').get()
                if 'Personal' in label:
                    label = 'Personal Webpage'
                elif label in [
                    'Curriculum Vitae',
                    'CV',
                    'cv'
                ]:
                    label = 'CV'
                elif label == url:
                    label = 'Other'
                else:
                    person.bio = person.bio.replace('\n' + label, label)
                    person.interests = person.interests.replace(
                        '\n' + label + '\n', label)
                person.urls.append({
                    'label': label,
                    'url': url,
                })
        relInfo = header_info.css(
            '.faculty__assistant_name')
        relPerson = {
            'title': relInfo.css(
                '::text').get(),
            'email': relInfo.css(
                'a::attr(href)').get(),
            'name': relInfo.css(
                'a::text').get(),
            'phone': relInfo.css(
                '.faculty__assistant-phone::text').get()
        }
        person.relatedPeople = []
        person.relatedPeople.append(relPerson)
        cInfo = [
            'Telephone: ' +
            header_info.css('.faculty__phone-number::text').get().strip(),
        ]
        person.contactInfo = cInfo[0]
        more_urls = header_info.css('.faculty__website a')
        for link in more_urls:
            person.urls.append(
                {'label': 'Laboratory: ' + link.css('::text').get(), 'url': link.css("::attr(href)").get()})
        more_urls = header_info.css('.faculty__affiliated-centers a')
        for link in more_urls:
            person.urls.append(
                {'label': 'Afflicted Centers: ' + link.css('::text').get(), 'url': link.css("::attr(href)").get()})
        extraInfo = header_info.css('.faculty__sidebar-content-group')
        for exInfWrapper in extraInfo:
            for exInf in exInfWrapper.xpath('div'):
                if exInf.xpath('@class').get() not in [
                    'faculty__phone-number',
                    'faculty__email',
                    'faculty__room',
                    'faculty__website',
                    'faculty__affiliated-centers',
                    'faculty__assistant_name',
                    'faculty__assistant-phone',
                ]:
                    print('----------------- DEBUG -----------------')
                    print(person.urls[0]['url'])
                    print(exInf.xpath('@class').get())
        person.save()

    def parsePeople(self, response):
        urls = []
        field_name = response.css('h1.research-area-page__title::text').get()
        people = response.css(
            '.research-area-page__related-faculty')[0].css(
            '.research-area-page__faculty-member')
        for person in people:
            name = person.css(".research-area-page__faculty-member > a")
            person_urls = [{'label': 'University Page',
                            'url': name.css("::attr(href)").get()}]
            name = name.css('::text').get()
            university = University.objects.get(id=self.id)
            if university.logo == None:
                self.webdriver.get(response.request.url)
                self.webdriver.implicitly_wait(2)
                university.logo = self.webdriver.find_elements_by_css_selector(
                    '.logo--mit')[0].value_of_css_property('background-image')
                university.logo = university.logo[5:]
                university.logo = university.logo[0:len(university.logo) - 2]
                university.color = 'mit-white'
                university.save()
            hindex = ''
            try:
                person = Researcher.objects.get(
                    name=name, university=university)
                person.fields[self.currentDep].append(field_name)
                person.save()
            except Researcher.DoesNotExist:
                urls.append(person_urls[0]['url'])
                person = Researcher.objects.create(
                    name=name,
                    urls=person_urls,
                    university=university,
                    fields={self.currentDep: [field_name]},
                    hindex=hindex,
                )
        return response.follow_all(urls, self.eachPersonInfo)
