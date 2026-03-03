from django.contrib.auth import authenticate
from rest_framework.authtoken.models import Token
from rest_framework.permissions import AllowAny
from rest_framework.response import Response
from rest_framework.status import (
    HTTP_200_OK,
    HTTP_400_BAD_REQUEST,
    HTTP_401_UNAUTHORIZED,
)
from rest_framework.views import APIView

from users.serializers import LoginSerializer, RegisterSerializer


class LoginView(APIView):
    permission_classes = [AllowAny]

    def post(self, request):
        serializer = LoginSerializer(data=request.data)
        if serializer.is_valid():
            username = serializer.validated_data.get("username")  # type: ignore
            password = serializer.validated_data.get("password")  # type: ignore

            user = authenticate(request, username=username, password=password)
            if user:
                token, _ = Token.objects.get_or_create(user=user)  # type: ignore
                return Response({"token": token.key}, status=HTTP_200_OK)

            return Response(
                {"message": "Invalid credentials"}, status=HTTP_401_UNAUTHORIZED
            )
        return Response(serializer.errors, status=HTTP_400_BAD_REQUEST)


class RegisterView(APIView):
    permission_classes = [AllowAny]

    def post(self, request):
        serializer = RegisterSerializer(data=request.data)
        if serializer.is_valid():
            return Response({"message": "Hello, World!"})
        return Response(serializer.errors, status=400)


class LogoutView(APIView):
    def post(self, request):
        serializer = RegisterSerializer(data=request.data)
        if serializer.is_valid():
            return Response({"message": "Hello, World!"})
        return Response(serializer.errors, status=400)
