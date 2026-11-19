from django.contrib.auth.models import User
from django.db import models
from django_ckeditor_5.fields import CKEditor5Field


# Create your models here.
class Category(models.Model):
    name = models.CharField(max_length=50, unique=True)
    enabled = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        verbose_name_plural = "Categories"

    def __str__(self):
        return self.name


BLOG_STATUS_CHOICES = ((0, "Draft"), (1, "Published"))


class Blog(models.Model):
    title = models.CharField(max_length=200)
    slug = models.SlugField(max_length=250, unique=True, blank=True)
    category = models.ForeignKey(
        Category, on_delete=models.CASCADE, related_name="blogs"
    )
    author = models.ForeignKey(User, on_delete=models.CASCADE, related_name="blogs")
    featured_image = models.ImageField(upload_to="uploads/%Y/%m/%d")
    short_description = models.TextField(max_length=500)
    blog_body = CKEditor5Field("Body", config_name="default")
    status = models.IntegerField(choices=BLOG_STATUS_CHOICES, default=0)
    is_featured = models.BooleanField(default=False)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    def __str__(self):
        return f"{self.title} by {self.author}"
