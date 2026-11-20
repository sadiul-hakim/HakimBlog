from django.shortcuts import get_object_or_404, render
from django.db.models import Q

from .models import Blog, Category


def home(request):
    posts = (
        Blog.objects.select_related("author")
        .filter(is_featured=False, status=1)
        .order_by("-updated_at")[:15]
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


def posts_by_search(request):
    text = request.GET.get("text")
    posts = Blog.objects.filter(
        Q(title__icontains=text)
        | Q(short_description__icontains=text)
        | Q(blog_body__icontains=text),
        status=1,
    )
    context = {"posts": posts, "text": text}
    return render(request, "posts_by_search.html", context)


def post(request, slug):
    post = get_object_or_404(
        Blog.objects.select_related("author", "category"), slug=slug
    )
    related_posts = (
        Blog.objects.select_related("author", "category")
        .exclude(pk=post.id)
        .filter(category=post.category, status=1)
        .order_by("-updated_at")[:5]
    )

    context = {"post": post, "related_posts": related_posts}
    return render(render, "post.html", context)
