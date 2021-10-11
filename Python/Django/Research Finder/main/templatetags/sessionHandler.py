import re
from django import template
from django.conf import settings
from django.apps import apps

numeric_test = re.compile("^\d+$")
register = template.Library()


def parseSessionVar(value, type):
    if type == "label":
        return apps.get_app_config("main").getFilters(True)[value['key']]
    elif type == "filter-type":
        return value['key']
    else:
        return value['value']


register.filter('parseSessionVar', parseSessionVar)
