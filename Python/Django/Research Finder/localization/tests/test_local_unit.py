from django.test import TestCase
from localization.models import *

class ModelTests(TestCase):
	def setUp(self):
		Country.objects.create(name='asdef1', short_name='af')
		City.objects.create(name='asdef1', country=Country.objects.get(id=1))
		Location.objects.create(country=Country.objects.get(id=1), city=City.objects.get(id=1), gmap='laksjdlakjdasl')

	def test_country(self):
		self.assertEqual(True, True)