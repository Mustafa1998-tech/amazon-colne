# Amazon Clone Helm Chart

This Helm chart deploys the Amazon Clone application along with its dependencies on a Kubernetes cluster.

## Prerequisites

- Kubernetes 1.19+
- Helm 3.2.0+
- PV provisioner support in the underlying infrastructure

## Installing the Chart

To install the chart with the release name `amazon-clone`:

```bash
helm repo add bitnami https://charts.bitnami.com/bitnami
helm repo add prometheus-community https://prometheus-community.github.io/helm-charts
helm repo add grafana https://grafana.github.io/helm-charts
helm repo update

helm install amazon-clone ./chart \
  --namespace amazon-clone \
  --create-namespace
```

## Uninstalling the Chart

To uninstall/delete the `amazon-clone` deployment:

```bash
helm uninstall amazon-clone -n amazon-clone
```

## Configuration

The following table lists the configurable parameters of the chart and their default values.

| Parameter | Description | Default |
|-----------|-------------|---------|
| `replicaCount` | Number of replicas | `1` |
| `image.repository` | Image repository | `your-docker-username/amazon-clone` |
| `image.tag` | Image tag | `latest` |
| `image.pullPolicy` | Image pull policy | `IfNotPresent` |
| `service.type` | Kubernetes service type | `ClusterIP` |
| `service.port` | Service port | `80` |
| `ingress.enabled` | Enable ingress | `false` |
| `ingress.hosts` | Ingress hosts | `chart-example.local` |
| `resources` | Resource requests/limits | `{}` |
| `mysql.enabled` | Enable MySQL | `true` |
| `mysql.auth.rootPassword` | MySQL root password | `rootpassword` |
| `mysql.auth.database` | Database name | `amazon_clone` |
| `mysql.auth.username` | Database user | `user` |
| `mysql.auth.password` | Database password | `password` |
| `prometheus.enabled` | Enable Prometheus | `true` |
| `grafana.enabled` | Enable Grafana | `true` |
| `grafana.adminUser` | Grafana admin username | `admin` |
| `grafana.adminPassword` | Grafana admin password | `admin` |

## Accessing the Application

After installation, you can access the application using the following commands:

```bash
# Get the application URL
kubectl get svc -n amazon-clone

# Access Prometheus UI (if enabled)
kubectl port-forward svc/amazon-clone-prometheus-server -n amazon-clone 9090:80

# Access Grafana (if enabled)
kubectl port-forward svc/amazon-clone-grafana -n amazon-clone 3000:80
```

## Persistence

The chart mounts a [Persistent Volume](https://kubernetes.io/docs/concepts/storage/persistent-volumes/) for MySQL, Prometheus, and Grafana. The volume is created using dynamic volume provisioning.

## Upgrading

To upgrade the chart with the release name `amazon-clone`:

```bash
helm upgrade amazon-clone ./chart -n amazon-clone
```

## Troubleshooting

If you encounter issues, check the logs of the pods:

```bash
kubectl logs -l app.kubernetes.io/name=amazon-clone -n amazon-clone
```

For more information on Helm charts, see the [Helm documentation](https://helm.sh/docs/).
