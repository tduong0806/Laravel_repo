FROM quay.io/minio/minio

ENV MINIO_ACCESS_KEY your_access_key
ENV MINIO_SECRET_KEY your_secret_key

EXPOSE 9000/tcp

CMD ["server", "-a", "0.0.0.0", "/data"]