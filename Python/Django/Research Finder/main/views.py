from django.shortcuts import render
from django.utils.translation import gettext as _
from django.utils.translation import get_language, activate
from django.conf import settings
from django.apps import apps
from django.middleware.csrf import get_token
from django.http import JsonResponse
from django.db import IntegrityError
from .models import *
from localization.models import *
import csv
import json
import re
import glob
# Create your views here.


def home(request):
    filter_types = apps.get_app_config('main').getFilters()
    sort_types = {
        _("University"): [
            {
                "label": _("Rank In Research Field"),
                "value": "uni-rank-in",
            },
        ],
        _("Professor"): [
            {
                "label": "HIndex",
                "value": "prof-hindex",
            },
        ],
    }
    results = Researcher.objects.all()
    # print(results[0].fields)
    return render(request, 'main/home.html', {
        "filters": filter_types,
        "sorters": sort_types,
        "APP_NAME": settings.APP_NAME,
        "csrf_token": get_token(request),
        "results": results
    })


def updateURLs(request, specificUniId=None):
    if specificUniId == None:
        # Truncate DB
        Researcher.objects.all().delete()
        University.objects.all().delete()
        Location.objects.all().delete()
        City.objects.all().delete()
        Country.objects.all().delete()

    csvList = glob.glob(str(settings.BASE_DIR) +
                        settings.STATIC_URL + "files/*50.csv")
    urls = {}
    currentField = ''
    currentRank = None
    for csvPath in csvList:
        with open(csvPath) as csvFile:
            csvReader = csv.DictReader(csvFile)
            for rows in csvReader:
                currentRank = rows['Rank'] if len(
                    rows['Rank'].strip()) > 0 else currentRank
                if specificUniId != None and currentRank != specificUniId:
                    continue
                if len(rows['Rank'].strip()) > 0:
                    if len(rows['URL'].strip()) == 0:
                        continue
                    # New Uni
                    cnt = Country.objects.create(
                        name=rows['Country'].strip(), short_name="sn")
                    ct = City.objects.create(
                        name=rows['Country'].strip(), country=cnt)
                    loc = Location.objects.create(
                        country=cnt,
                        city=ct,
                        gmap="gm"
                    )
                    try:
                        mainURL = re.search(
                            '^([https\:\/\/]+[A-z.]+)', rows['URL'].strip()).group(1)
                    except AttributeError:
                        print(rows)
                        exit()
                    currentDep = rows['Department'].strip()
                    if specificUniId == None:
                        try:
                            currentUni = University.objects.create(
                                name=rows['University'].strip(),
                                url=mainURL,
                                location=loc,
                                ranking={currentDep: rows['Rank'].strip()}
                            )
                        except:
                            # Unique Contsraint on Name Fails:
                            if len(rows['University'].strip()) > 0:
                                currentUni = University.objects.get(
                                    name=rows['University'].strip())
                            else:
                                currentUni = University.objects.get(
                                    name=currentUni.name)
                            currentUni.ranking[currentDep] = rows['Rank'].strip(
                            )
                            currentUni.save()
                    else:
                        try:
                            currentUni = University.objects.get(
                                name=rows['University'].strip())
                        except:
                            # None Existent
                            currentUni = University.objects.create(
                                name=rows['University'].strip(),
                                url=mainURL,
                                location=loc,
                                ranking={currentDep: rows['Rank'].strip()}
                            )
                        currentUni.ranking[currentDep] = rows['Rank'].strip()
                        currentUni.save()
                    if currentDep in urls:
                        pass
                    else:
                        urls[currentDep] = {}
                    urls[currentDep][currentUni.id] = {
                        "name": currentUni.name, "urls": []
                    }
                # RA -> URL
                if len(rows['Research Subarea'].strip()) == 0:
                    urls[currentDep][currentUni.id]['urls'].append(
                        {rows['Research Area']: rows['URL'].strip()})
                else:
                    if (rows['Research Area'] in urls[currentDep][currentUni.id]['urls']) or (len(rows['Research Area'].strip()) == 0):
                        pass
                    else:
                        currentField = rows['Research Area']
                        urls[currentDep][currentUni.id]['urls'].append(
                            {currentField: []})
                        index = getRFieldIndexFromURLList(
                            urls[currentDep][currentUni.id]['urls'], currentField)
                    print(index, currentField)
                    urls[currentDep][currentUni.id]['urls'][index][currentField].append(
                        {rows['Research Subarea']: rows['URL'].strip()})

    jsonPath = str(settings.BASE_DIR) + "/urls.json"
    with open(jsonPath, 'w') as jsonFile:
        jsonFile.write(json.dumps(urls, indent=4))
        jsonFile.close()
    return JsonResponse({'success': True})


def getRFieldIndexFromURLList(urls, researchField):
    for i in range(len(urls)):
        for key, value in urls[i].items():
            if key == researchField:
                return i


def resetSessionArr(sessionArr):
    origin = sessionArr
    i = 0
    # print(origin)
    sessionArr = []
    for item in origin:
        sessionArr.append(item)
        i += 1
    return sessionArr


def filterAdd(request):
    # request.session.clear()
    offset = 0
    try:
        offset = len(request.session['currentFilters'])
        request.session['currentFilters'] = resetSessionArr(
            request.session['currentFilters'])
    except KeyError:
        request.session['currentFilters'] = []
    # if request.session.has_key('currentFilters') == False:
    #   request.session['currentFilters'] = []
    # else:
    #   offset = len(request.session['currentFilters'])
    #   request.session['currentFilters'] = resetSessionArr(request.session['currentFilters'])
    # Rebuild the data
    data = [(request.POST[item]) for item in request.POST]
    # data = [{data[i]: data[i+1]} for i in range(0, len(data), 2)]
    for i in range(offset, len(data) + offset, 2):
        request.session['currentFilters'].append(
            {"key": data[i - offset], "value": data[i + 1 - offset]})
    # print(request.session['currentFilters'])
    return JsonResponse({'success': True})


def filterDel(request):
    try:
        request.session['currentFilters'].pop(int(request.POST['key']))
        request.session['currentFilters'] = resetSessionArr(
            request.session['currentFilters'])
    except KeyError:
        pass
    return JsonResponse({'success': True})
    # if request.session.has_key('currentFilters') == False:
    #   return JsonResponse({'success':True})
    # request.session['currentFilters'].pop(int(request.POST['key']))
    # request.session['currentFilters'] = resetSessionArr(request.session['currentFilters'])
    # return JsonResponse({'success':True})


def filterList(request):
    return JsonResponse(apps.get_app_config('main').getFilters(True))
