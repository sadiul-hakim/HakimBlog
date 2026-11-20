from .models import NavigationLink, Setting


def get_settings(request):
    setting = Setting.get_settings()
    return {"setting": setting}


def get_links(request):
    links = NavigationLink.objects.all()
    return {"social_links": links}
