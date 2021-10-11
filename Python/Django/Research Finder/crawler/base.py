import json
from django.conf import settings
from importlib import import_module


class UniversityURLList():
    def __init__(self):
        self.list = {}
        self.webdriver = None

    def addUni(self, uni):
        uni.webdriver = self.webdriver
        self.list[int(uni.id)] = uni

    def getByURL(self, url):
        for id in self.list:
            if url in list(self.list[id].urls.values())[0]:
                dep = [dep for dep, urls in self.list[id].urls.items()
                       if url in urls]
                self.list[id].setDepartment(dep[0])
                return self.list[id]


class EachUniversity():
    def __init__(self, id, name):
        self.id = id
        self.name = name
        self.currentDep = ""
        self.urls = {}
        self.webdriver = None

    def classifiedProcess(self, response):
        model = import_module(
            'crawler.' + self.name.replace(' ', '')).UniversityCrawler(self.id, department=self.currentDep, webdriver=self.webdriver)
        return model.parsePeople(response)

    def addURL(self, url, dep):
        try:
            self.urls[dep].append(url)
        except KeyError:
            self.urls[dep] = []
            self.urls[dep].append(url)

    def setDepartment(self, department):
        self.currentDep = department
