import { TrainFront, Radio, AlertTriangle, Activity } from 'lucide-react'
import Card from '../components/Card'
import { trains, alerts } from '../data/mockData'

const onlineSensorsCount = 47
const averageSpeed = '85 km/h'

export default function Dashboard() {
  const activeAlerts = alerts.length

  const stats = [
    {
      label: 'Active Trains',
      value: trains.length.toString(),
      icon: TrainFront,
      iconBg: 'bg-gomobi-blue/25',
      iconColor: 'text-[#5b8fd6]',
    },
    {
      label: 'Online Sensors',
      value: onlineSensorsCount.toString(),
      icon: Radio,
      iconBg: 'bg-emerald-50',
      iconColor: 'text-emerald-500',
    },
    {
      label: 'Active Alerts',
      value: activeAlerts.toString(),
      icon: AlertTriangle,
      iconBg: 'bg-red-50',
      iconColor: 'text-red-500',
    },
    {
      label: 'Average Speed',
      value: averageSpeed,
      icon: Activity,
      iconBg: 'bg-gomobi-purple/15',
      iconColor: 'text-gomobi-purple',
    },
  ]

  return (
    <div>
      <h1 className="font-heading text-2xl font-bold text-gomobi-dark">Dashboard</h1>
      <p className="mt-1 text-sm text-gray-500 font-body">
        Real-time overview of railway operations
      </p>

      <div className="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {stats.map(({ label, value, icon: Icon, iconBg, iconColor }) => (
          <Card key={label} className="flex items-center justify-between">
            <div>
              <p className="text-xs text-gray-500 font-body">{label}</p>
              <p className="mt-1 font-heading text-xl font-bold text-gomobi-dark">{value}</p>
            </div>
            <div className={`flex h-10 w-10 items-center justify-center rounded-lg ${iconBg}`}>
              <Icon size={18} className={iconColor} />
            </div>
          </Card>
        ))}
      </div>

      <div className="mt-6">
        <Card>
          <p className="text-sm font-semibold text-gomobi-dark font-body">Recent Alerts</p>
          {alerts.length === 0 ? (
            <p className="mt-8 mb-4 text-center text-sm text-gray-400 font-body">
              No active alerts
            </p>
          ) : (
            <div className="mt-3 flex flex-col gap-2">
              {alerts.map((alert) => (
                <div
                  key={alert.id}
                  className="rounded-lg border border-gray-100 px-3 py-2.5 text-sm font-body text-gomobi-dark"
                >
                  {alert.message}
                </div>
              ))}
            </div>
          )}
        </Card>
      </div>
    </div>
  )
}
