from django.db import models


class Task(models.Model):
    title = models.CharField(max_length=255)
    description = models.TextField()
    completed = models.BooleanField(default=False)

    def _handle_done(self):
        self.completed = not self.completed
        self.save()
