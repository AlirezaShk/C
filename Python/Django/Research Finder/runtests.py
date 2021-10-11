#!/usr/bin/env python
import os
import sys
import requests
import django
import scrapy

from django.conf import settings
from django.test.utils import get_runner

if __name__ == "__main__":
    os.environ['DJANGO_SETTINGS_MODULE'] = 'research_find.settings'
    django.setup()
    TestRunner = get_runner(settings)
    test_runner = TestRunner()
    print(settings.DEFAULT_AUTO_FIELD)
    test_runner.run_tests(settings.TESTS_DIRS)