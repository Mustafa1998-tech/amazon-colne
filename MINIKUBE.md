# Deploying Amazon Clone on Minikube

This guide will help you deploy the Amazon Clone application on a local Minikube cluster.

## Prerequisites

- [Minikube](https://minikube.sigs.k8s.io/docs/start/) installed
- [kubectl](https://kubernetes.io/docs/tasks/tools/) installed
- [Helm](https://helm.sh/docs/intro/install/) 3.x installed
- [Docker](https://docs.docker.com/get-docker/) installed (or another container runtime)

## 1. Start Minikube

Start Minikube with sufficient resources:

```bash
minikube start \
  --cpus=4 \
  --memory=8192 \
  --disk-size=40g \
  --driver=docker \
  --kubernetes-version=v1.26.0

# Enable required addons
minikube addons enable ingress
minikube addons enable metrics-server
```

## 2. Build and Load Docker Images

Build your application Docker image and load it into Minikube:

```bash
# Build the Docker image
docker build -t amazon-clone:latest -f backend/Dockerfile .

# Load the image into Minikube
minikube image load amazon-clone:latest
```

## 3. Install Dependencies

Add the required Helm repositories:

```bash
helm repo add bitnami https://charts.bitnami.com/bitnami
helm repo add prometheus-community https://prometheus-community.github.io/helm-charts
helm repo add grafana https://grafana.github.io/helm-charts
helm repo update
```

## 4. Deploy the Application

Create a namespace and install the chart:

```bash
# Create namespace
kubectl create namespace amazon-clone

# Install the chart with Minikube-specific values
helm install amazon-clone ./chart \
  --namespace amazon-clone \
  --set service.type=NodePort \
  --set mysql.primary.persistence.enabled=false \
  --set prometheus.server.persistentVolume.enabled=false \
  --set grafana.persistence.enabled=false
```

## 5. Access the Application

Get the Minikube IP and NodePort for the application:

```bash
# Get Minikube IP
MINIKUBE_IP=$(minikube ip)

# Get NodePort for the application
NODE_PORT=$(kubectl get -n amazon-clone svc/amazon-clone -o jsonpath='{.spec.ports[0].nodePort}')

echo "Access the application at: http://$MINIKUBE_IP:$NODE_PORT"
```

## 6. Access Monitoring Tools

### Prometheus

```bash
kubectl port-forward -n amazon-clone svc/amazon-clone-prometheus-server 9090:80 &
# Access at http://localhost:9090
```

### Grafana

```bash
# Get the Grafana admin password
kubectl get secret -n amazon-clone amazon-clone-grafana -o jsonpath="{.data.admin-password}" | base64 --decode ; echo

# Port-forward Grafana
kubectl port-forward -n amazon-clone svc/amazon-clone-grafana 3000:80 &
# Access at http://localhost:3000
# Default username: admin
```

## 7. View Logs

```bash
# View application logs
kubectl logs -n amazon-clone -l app.kubernetes.io/name=amazon-clone -f

# View all resources in the namespace
kubectl get all -n amazon-clone
```

## 8. Update and Redeploy

After making changes to your application:

```bash
# Rebuild and reload the image
docker build -t amazon-clone:latest -f backend/Dockerfile .
minikube image load amazon-clone:latest

# Restart the deployment to pick up the new image
kubectl rollout restart deployment -n amazon-clone amazon-clone
```

## 9. Clean Up

To remove the deployment:

```bash
# Uninstall the release
helm uninstall amazon-clone -n amazon-clone

# Delete the namespace
kubectl delete namespace amazon-clone

# Stop Minikube (when done)
minikube stop
```
## Troubleshooting

### Minikube Dashboard

Access the Kubernetes dashboard:

```bash
minikube dashboard
```

### Check Pod Status

```bash
kubectl get pods -n amazon-clone
kubectl describe pod <pod-name> -n amazon-clone
kubectl logs <pod-name> -n amazon-clone
```

### Check Persistent Volumes

```bash
kubectl get pv,pvc -n amazon-clone
```

### Check Services

```bash
kubectl get svc -n amazon-clone
minikube service list
```

For more information, refer to the [Minikube documentation](https://minikube.sigs.k8s.io/docs/).
