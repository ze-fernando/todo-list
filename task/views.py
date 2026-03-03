from rest_framework.response import Response
from rest_framework.status import (
    HTTP_200_OK,
    HTTP_201_CREATED,
    HTTP_204_NO_CONTENT,
    HTTP_400_BAD_REQUEST,
)
from rest_framework.views import APIView

from .models import Task
from .serializers import TaskSerializer


class TaskView(APIView):
    def get(self, request):
        user = request.user
        tasks = Task.objects.filter(user=user)  # type: ignore
        serializer = TaskSerializer(tasks, many=True)
        return Response(serializer.data, status=HTTP_200_OK)

    def post(self, request):
        user = request.user
        request.data["user"] = user.id
        serializer = TaskSerializer(data=request.data)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data, status=HTTP_201_CREATED)
        return Response(serializer.errors, status=HTTP_400_BAD_REQUEST)


class TaskDetailView(APIView):
    def get(self, request, pk):
        task = Task.objects.get(pk=pk)  # type: ignore
        user = request.user
        request.data["user"] = user.id
        serializer = TaskSerializer(task)
        return Response(serializer.data, status=HTTP_200_OK)

    def put(self, request, pk):
        task = Task.objects.get(pk=pk)  # type: ignore
        user = request.user
        request.data["user"] = user.id
        serializer = TaskSerializer(task, data=request.data)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data, status=HTTP_200_OK)
        return Response(serializer.errors, status=HTTP_400_BAD_REQUEST)

    def delete(self, request, pk):
        task = Task.objects.get(pk=pk)  # type: ignore
        task.delete()
        return Response(status=HTTP_204_NO_CONTENT)

    def patch(self, request, pk):
        task = Task.objects.get(pk=pk)  # type: ignore
        task._handle_done()
        user = request.user
        request.data["user"] = user.id
        serializer = TaskSerializer(task, data=request.data, partial=True)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data, status=HTTP_200_OK)
        return Response(serializer.errors, status=HTTP_400_BAD_REQUEST)
