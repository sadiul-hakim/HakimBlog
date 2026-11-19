from django.conf import settings
from django.conf.urls.static import static
from django.contrib import admin
from django.urls import include, path
from debug_toolbar.toolbar import debug_toolbar_urls


urlpatterns = [
    path("admin/", admin.site.urls),
    path("ckeditor5/", include("django_ckeditor_5.urls")),
    path("select2/", include("django_select2.urls")),
    path("", include("blog.urls")),
] + debug_toolbar_urls()

if settings.DEBUG:
    urlpatterns += static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)
