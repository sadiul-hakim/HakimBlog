from django.shortcuts import get_object_or_404, render

from .models import Blog, Category


def home(request):
    posts = (
        Blog.objects.select_related("author")
        .filter(is_featured=False, status=1)
        .order_by("updated_at")[:15]
    )
    featured_post = (
        Blog.objects.select_related("author")
        .filter(is_featured=True, status=1)
        .order_by("updated_at")
    )
    context = {"featured_post": featured_post, "posts": posts}
    return render(request, "home.html", context)


def posts_by_category(request, category_id):
    category = get_object_or_404(Category, pk=category_id)
    posts = Blog.objects.filter(status=1, category=category_id)
    context = {"posts": posts, "category": category}
    return render(request, "posts_by_category.html", context)
