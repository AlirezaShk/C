from django.db import models
from localization.models import Location

# Create your models here.


class University(models.Model):
    name = models.CharField(max_length=400, unique=True)
    url = models.CharField(max_length=200, default=None)
    location = models.ForeignKey(
        Location, on_delete=models.RESTRICT, default=None)
    ranking = models.JSONField(max_length=400, default=None)
    logo = models.TextField(null=True)
    color = models.CharField(max_length=40, null=True)

    def __str__(self):
        return self.name


class Article(models.Model):
    title = models.CharField(max_length=400)
    url = models.CharField(max_length=200, default=None)
    fields = models.JSONField(max_length=500, null=True)

    def __str__(self):
        return self.title


class Researcher(models.Model):
    name = models.CharField(max_length=400)
    email = models.CharField(max_length=250)
    urls = models.JSONField(max_length=800, null=True)
    articles = models.ManyToManyField(Article)
    university = models.ForeignKey(University, on_delete=models.RESTRICT)
    hindex = models.CharField(max_length=120, null=True)
    fields = models.JSONField(max_length=500, null=True)
    role = models.CharField(max_length=250, null=True)
    bio = models.TextField(null=True)
    interests = models.TextField(null=True)
    tags = models.JSONField(max_length=800, null=True)
    relatedPeople = models.JSONField(max_length=400, null=True)
    contactInfo = models.TextField(null=True)

    def __str__(self):
        return self.name
