from django.test import TestCase
from main.models import *
from localization.models import *

class ModelTests(TestCase):
	def setUp(self):
		Country.objects.create(name='asdef1', short_name='af')
		City.objects.create(name='asdef1', country=Country.objects.get(id=1))
		Location.objects.create(country=Country.objects.get(id=1), city=City.objects.get(id=1), gmap='laksjdlakjdasl')
		University.objects.create(name='asdef1', url='asdadasdasdasd', tags={"a":1},location=Location.objects.get(id=1))

	def test_university(self):
		self.assertEqual(True, True)