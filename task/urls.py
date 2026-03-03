from django.urls import path

from task.views import TaskDetailView, TaskView

urlpatterns = [
    path("", TaskView.as_view()),
    path("<int:pk>", TaskDetailView.as_view()),
]
