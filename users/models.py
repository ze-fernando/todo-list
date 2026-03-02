from django.db import models

from task.models import Task


class User(models.Model):
    username = models.CharField(max_length=150)
    email = models.EmailField(unique=True)
    password = models.CharField(max_length=128)

    tasks = models.ManyToManyField(Task, related_name="users")
