from django.contrib import admin

from .models import Blog, Category

admin.site.site_title = "Hakim Blog"
admin.site.site_header = "Hakim Blog"
admin.site.index_title = "Hakim Blog"


# Register your models here.
@admin.register(Category)
class CategoryAdmin(admin.ModelAdmin):
    list_display = (
        "name",
        "status",
        "created_at",
        "updated_at",
    )
    search_fields = (
        "id",
        "name",
    )
    list_filter = (
        "created_at",
        "status",
    )
    list_editable = ("status",)


@admin.register(Blog)
class BlogAdmin(admin.ModelAdmin):
    prepopulated_fields = {"slug": ("title",)}
    list_display = (
        "title",
        "category",
        "author",
        "status",
        "is_featured",
        "created_at",
        "updated_at",
    )
    search_fields = (
        "id",
        "title",
        "slug",
        "category__name",
        "author__username",
    )
    list_filter = (
        "category__name",
        "is_featured",
        "status",
        "created_at",
        "updated_at",
    )
    list_editable = (
        "status",
        "is_featured",
    )
