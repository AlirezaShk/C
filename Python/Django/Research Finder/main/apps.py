from django.apps import AppConfig
from django.utils.translation import gettext as _


class MainConfig(AppConfig):
    default_auto_field = 'django.db.models.BigAutoField'
    name = 'main'

    def getFilters(self, reverse=False):
        if reverse:
            return {
                "uni-name": _("University Name: "),
                "uni-region": _("University Region: "),
                "prof-name": _("Professor Name: "),
                "prof-field": _("Professor Field: "),
                "prof-role": _("Professor Role: "),
                "free": _("Free Word: ")
            }
        else:
            return {
                _("General"): [
                    {
                        "label": _("Free Word"),
                        "value": "free",
                    },
                ],
                _("University"): [
                    {
                        "label": _("Name"),
                        "value": "uni-name",
                    },
                    {
                        "label": _("Region"),
                        "value": "uni-region",
                    },
                ],
                _("Professor"): [
                    {
                        "label": "Name",
                        "value": "prof-name",
                    },
                    {
                        "label": _("Research Field"),
                        "value": "prof-field",
                    },
                    {
                        "label": _("Role"),
                        "value": "prof-role",
                    },
                ],
            }
