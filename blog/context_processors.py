from .models import Category


def get_categories(request):
    categories = Category.objects.filter(enabled=True)
    return {"categories": categories}
