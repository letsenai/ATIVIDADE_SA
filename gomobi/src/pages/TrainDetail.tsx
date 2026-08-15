import { useParams, Link } from 'react-router-dom'
import { ArrowLeft, MapPinned, Gauge, Flag, Clock, Radio } from 'lucide-react'
import { trains } from '../data/mockData'
import Card from '../components/Card'
import StatusBadge from '../components/StatusBadge'

const detailStatusMap: Record<string, string> = {
  Online: 'On Time',
  Warning: 'Delayed',
  Offline: 'Delayed',
}

export default function TrainDetail() {
  const { id } = useParams<{ id: string }>()
  const train = trains.find((t) => t.id === id)

  if (!train) {
    return (
      <div>
        <Link to="/trains" className="inline-flex items-center gap-1.5 text-sm text-gomobi-purple font-body">
          <ArrowLeft size={15} />
          Back to fleet
        </Link>
        <p className="mt-6 text-sm text-gray-500 font-body">Train not found.</p>
      </div>
    )
  }

  const detailStatus = detailStatusMap[train.status] ?? train.status

  const stats = [
    { label: 'Current Line', value: train.line.replace(' Line', ''), icon: MapPinned },
    { label: 'Current Speed', value: train.currentSpeed, icon: Gauge },
    { label: 'Destination', value: train.destination, icon: Flag },
    { label: 'ETA', value: train.eta, icon: Clock },
  ]

  return (
    <div>
      <Link
        to="/trains"
        className="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gomobi-dark font-body"
      >
        <ArrowLeft size={15} />
        Back
      </Link>

      <div className="mt-3 flex items-center gap-3">
        <h1 className="font-heading text-2xl font-bold text-gomobi-dark">{train.id}</h1>
        <StatusBadge status={detailStatus} showDot={false} />
      </div>
      <p className="mt-1 text-sm text-gray-500 font-body">
        Live telemetry and predictive diagnostics
      </p>

      <div className="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        {stats.map(({ label, value, icon: Icon }) => (
          <Card key={label} className="flex items-center gap-3">
            <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-gomobi-purple/10">
              <Icon size={16} className="text-gomobi-purple" />
            </div>
            <div>
              <p className="text-xs text-gray-500 font-body">{label}</p>
              <p className="mt-0.5 font-heading text-sm font-bold text-gomobi-dark">{value}</p>
            </div>
          </Card>
        ))}
      </div>

      <div className="mt-6">
        <div className="mb-2 flex items-center gap-2">
          <Radio size={15} className="text-gomobi-dark" />
          <p className="text-sm font-semibold text-gomobi-dark font-body">Connected Sensors Array</p>
        </div>
        <Card>
          <p className="py-6 text-center text-sm text-gray-400 font-body">
            No active sensors detected for this train.
          </p>
        </Card>
      </div>
    </div>
  )
}
