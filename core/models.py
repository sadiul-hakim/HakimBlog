from django.db import models
from django_ckeditor_5.fields import CKEditor5Field


class Setting(models.Model):
    site_name = models.CharField(max_length=150, default="My Blog")
    tagline = models.CharField(max_length=255, blank=True)
    logo = models.ImageField(upload_to="site/", blank=True, null=True)
    favicon = models.ImageField(upload_to="site/", blank=True, null=True)
    contact_email = models.EmailField(blank=True)
    contact_phone = models.CharField(max_length=30, blank=True)
    footer_text = models.CharField(max_length=255, blank=True)
    updated_at = models.DateTimeField(auto_now=True)
    about = CKEditor5Field("About", config_name="default")

    def __str__(self):
        return self.site_name

    @classmethod
    def get_settings(cls):
        obj, _ = cls.objects.get_or_create(pk=1)
        return obj


class NavigationLink(models.Model):
    title = models.CharField(max_length=100)
    icon = models.CharField(max_length=30, null=True, blank=True)

    url = models.CharField(max_length=500)

    is_external = models.BooleanField(default=False)

    enabled = models.BooleanField(default=True)

    order = models.PositiveIntegerField(default=0)

    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        ordering = ["order"]

    def __str__(self):
        return self.title
