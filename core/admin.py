from django.contrib import admin

from .models import NavigationLink, Setting

admin.site.register(Setting)


@admin.register(NavigationLink)
class NavigationLinkAdmin(admin.ModelAdmin):
    list_display = (
        "title",
        "url",
        "is_external",
        "enabled",
        "order",
    )

    list_editable = (
        "enabled",
        "order",
    )

    list_filter = (
        "enabled",
        "is_external",
    )

    search_fields = (
        "title",
        "url",
    )
