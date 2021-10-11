# import scrapy
# from shutil import which
# from scrapy_selenium import SeleniumRequest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

# SELENIUM_DRIVER_NAME = 'firefox'
# SELENIUM_DRIVER_EXECUTABLE_PATH = which('geckodriver')
# SELENIUM_DRIVER_ARGUMENTS=['-headless']


# class BlogSpider(scrapy.Spider):
#     name = 'blogspider'
#     start_urls = ['https://www.zyte.com/blog/']

#     def parse(self, response):
#         # for title in response.css('.oxy-post-title'):
#         #     yield {'title': title.css('::text').get()}
#         yield SeleniumRequest(url=response.request.url, callback=self.parse_result)

#         # for next_page in response.css('a.next'):
#         #     yield response.follow(next_page, self.parse)

#     def parse_result(self, response):
#         # current window


options = webdriver.ChromeOptions()
options.add_argument("--headless")
driver = webdriver.Chrome(
    '/home/alirezashk/apps/chromedriver', options=options)

driver.get(
    'https://www.google.com/search?q=Steven+Chu+%40+Stanford+University+h-index')

driver.find_elements_by_css_selector('#search a')[0].click()
hindex = WebDriverWait(driver, 10).until(
    EC.presence_of_element_located(
        (By.XPATH, "//a[text()='h-index']/../../td[2]"))
)
print(hindex.get_attribute('textContent'))
driver.quit()
# driver.get(
#     "https://www.google.com")
# i = 1
# while i > 0:
#     pass
# try:
# driver.find_element_by_class_name('button')
#     button.click()
# finally:
#     driver.quit()

# first_tab = browser.window_handles[0]
# # create new tab
# browser.execute_script("window.open()")
# # move to new tab
# new_tab = browser.window_handles[1]
# browser.switch_to.window(new_tab)
# browser.get('https://gmail.com')
# # switch to first tab
# browser.switch_to.window(first_tab)
