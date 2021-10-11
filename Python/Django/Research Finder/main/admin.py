from django.contrib import admin
from .models import *

# Register your models here.
# admin.site.register(Researcher)
admin.site.register(Article)
admin.site.register(University)

@admin.register(Researcher)
class ResearcherAdmin(admin.ModelAdmin):
    list_display = ("id", "name", "fields", "university")