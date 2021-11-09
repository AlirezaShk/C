import os
import django

os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'research_find.settings')
django.setup()

import scrapy
import json
from main.models import *
from crawler.base import *
from selenium import webdriver

from shutil import which

SELENIUM_DRIVER_NAME = 'chrome'
SELENIUM_DRIVER_EXECUTABLE_PATH = which('geckodriver')
SELENIUM_DRIVER_ARGUMENTS = ['--headless']
DOWNLOADER_MIDDLEWARES = {
    'scrapy_selenium.SeleniumMiddleware': 800
}


class UniSpider(scrapy.Spider):
    name = 'univspider'
    start_urls = []
    unis = UniversityURLList()

    def __init__(self):

        # options = webdriver.ChromeOptions()
        # options.add_argument("--headless")
        # driver = webdriver.Chrome('./'+
        #     settings.STATIC_URL + 'config/chromedriver', options=options)
        # self.unis.webdriver = driver
        # driver.quit()
        # driver.get(
        #     'https://www.google.com/search?q=Steven+Chu+%40+Stanford+University+h-index')

        # driver.find_elements_by_css_selector('#search a')[0].click()
        # hindex = WebDriverWait(driver, 10).until(
        #     EC.presence_of_element_located(
        #         (By.XPATH, "//a[text()='h-index']/../../td[2]"))
        # )
        # print(hindex.get_attribute('textContent'))

        Researcher.objects.all().delete()

        jsonPath = "urls_errors.json"
        open(jsonPath, 'w').close()
        with open('urls.json') as jsonFile:
            data = json.load(jsonFile)
            uni_index = 0
            for dep, dep_data in data.items():
                for uni_id, uni_data in dep_data.items():
                    uni = EachUniversity(int(uni_id), uni_data['name'])
                    for field_url in uni_data['urls']:
                        for field in field_url:
                            if field_url[field] in self.start_urls:
                                pass
                            else:
                                self.start_urls.append(field_url[field])
                            uni.addURL(dep=dep, url=field_url[field])
                    self.unis.addUni(uni)

    def parse(self, response):
        return self.unis.getByURL(response.request.url).classifiedProcess(response)

        # for title in response.css('.oxy-post-title'):
        #     yield {'title': title.css('::text').get()}

        # for next_page in response.css('a.next'):
        #     yield response.follow(next_page, self.parse)
