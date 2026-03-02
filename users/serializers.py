from rest_framework import serializers

from users.models import User


class LoginSerializer(serializers.Serializer):
    password = serializers.CharField(write_only=True)

    class Meta:
        model = User
        fields = ["username", "password"]


class RegisterSerializer(serializers.Serializer):
    password = serializers.CharField(write_only=True)

    class Meta:
        model = User
