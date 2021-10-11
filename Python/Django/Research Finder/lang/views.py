from django.shortcuts import render
from django.utils.translation import gettext as _
from django.utils.translation import get_language, activate

# Create your views here.

def translate(language):
	cur_language = get_language()
	try:
		activate(language)
		text = gettext('home_page')
	finally:
		activate(cur_language)
	return text

def home(response):
	trans = translate(language='de')
	return render(response, 'lang/home.html', {'trans': trans})