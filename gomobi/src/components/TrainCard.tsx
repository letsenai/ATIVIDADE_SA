import { Link } from 'react-router-dom'
import { Thermometer, Droplets, Disc, CheckCircle2, MapPin, ArrowUpRight } from 'lucide-react'
import type { Train } from '../types'
import StatusBadge from './StatusBadge'

export default function TrainCard({ train }: { train: Train }) {
  return (
    <div className="rounded-xl border border-gray-100 bg-white p-4 shadow-card">
      <div className="flex items-start justify-between">
        <div>
          <p className="font-heading text-sm font-semibold text-gomobi-dark">{train.id}</p>
          <p className="text-xs text-gomobi-purple font-body">{train.line}</p>
        </div>
        <StatusBadge status={train.status} />
      </div>

      <div className="mt-3 flex items-center justify-between text-xs font-body">
        <div className="flex items-center gap-1.5 text-gray-500">
          <MapPin size={13} />
          <span>Next Station</span>
        </div>
        <div className="text-gray-500">ETA</div>
      </div>
      <div className="mt-0.5 flex items-center justify-between text-sm font-body">
        <span className="text-gomobi-dark font-medium">{train.nextStation}</span>
        <span className="text-gomobi-dark font-medium">{train.eta}</span>
      </div>

      <div className="mt-3 grid grid-cols-4 gap-2 border-t border-gray-50 pt-3 text-center">
        <div className="flex flex-col items-center gap-1">
          <Thermometer size={14} className="text-gray-400" />
          <span className="text-xs text-gray-600 font-body">{train.reading.temperature}</span>
        </div>
        <div className="flex flex-col items-center gap-1">
          <Droplets size={14} className="text-gray-400" />
          <span className="text-xs text-gray-600 font-body">{train.reading.humidity}</span>
        </div>
        <div className="flex flex-col items-center gap-1">
          <Disc size={14} className="text-gray-400" />
          <span className="text-xs text-gray-600 font-body">{train.reading.brake}</span>
        </div>
        <div className="flex flex-col items-center gap-1">
          <CheckCircle2 size={14} className="text-gray-400" />
          <span className="text-xs text-gray-600 font-body">{train.reading.status}</span>
        </div>
      </div>

      <Link
        to={`/trains/${train.id}`}
        className="mt-3 flex w-full items-center justify-center gap-1.5 rounded-lg border border-gray-200 py-2 text-xs font-semibold font-body text-gomobi-dark hover:bg-gray-50"
      >
        View Sensor Telemetry
        <ArrowUpRight size={13} />
      </Link>
    </div>
  )
}
