import re
from django import template
from django.conf import settings

numeric_test = re.compile("^\d+$")
register = template.Library()


def handleNewLines(value):
    print(settings.NEW_LINE_TAG)
    return value.replace(settings.NEW_LINE_TAG, '<br>')

register.filter('handleNewLines', handleNewLines)