from django.urls import path
from . import views

urlpatterns = [
    path('', views.home, name='home'),
    path('api/update', views.updateURLs, name='update-urls'),
    path('api/filter/add', views.filterAdd, name='add-filter'),
    path('api/filter/delete', views.filterDel, name='del-filter'),
    path('api/filter/get', views.filterList, name='get-filter'),
]
