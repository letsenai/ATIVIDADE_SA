import RailMap from '../components/RailMap'
import TrainCard from '../components/TrainCard'
import { trains } from '../data/mockData'

export default function Trains() {
  return (
    <div>
      <h1 className="font-heading text-2xl font-bold text-gomobi-dark">Live Fleet Tracking</h1>
      <p className="mt-1 text-sm text-gray-500 font-body">
        Monitor real-time GPS locations and sensor-derived health profiles of all active units.
      </p>

      <div className="mt-6">
        <RailMap />
      </div>

      <div className="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        {trains.map((train) => (
          <TrainCard key={train.id} train={train} />
        ))}
      </div>
    </div>
  )
}
