from django.shortcuts import render

from .models import Blog, Category


def home(request):
    posts = Blog.objects.filter(is_featured=False, status=1).order_by("updated_at")[:15]
    featured_post = Blog.objects.filter(is_featured=True, status=1).order_by(
        "updated_at"
    )
    categories = Category.objects.filter(enabled=True)
    context = {"categories": categories, "featured_post": featured_post, "posts": posts}
    return render(request, "home.html", context)
