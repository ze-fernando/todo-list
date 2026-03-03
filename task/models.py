from django.db import models

from users.models import User


class Task(models.Model):
    title = models.CharField(max_length=255)
    description = models.TextField()
    completed = models.BooleanField(default=False)  # type: ignore

    user = models.ForeignKey(User, on_delete=models.CASCADE)

    def _handle_done(self):
        self.completed = not self.completed
        self.save()
