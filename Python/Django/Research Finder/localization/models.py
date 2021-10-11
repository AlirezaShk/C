from django.db import models

# Create your models here.
class Country(models.Model):
	name = models.CharField(max_length=80)
	short_name = models.CharField(max_length=10, default=None)

	def __str__(self):
		return self.name

class City(models.Model):
	name = models.CharField(max_length=80)
	country = models.ForeignKey(Country, on_delete=models.RESTRICT)

	def __str__(self):
		return str(self.country) + "-> " + self.name

class Location(models.Model):
	city = models.ForeignKey(City, on_delete=models.RESTRICT)
	country = models.ForeignKey(Country, on_delete=models.RESTRICT)
	gmap = models.CharField(max_length=300, default=None)

	def __str__(self):
		return "city: " + str(self.city)