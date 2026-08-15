import { useMemo, useState, type FormEvent } from 'react'
import { Search, Plus, Pencil, Trash2 } from 'lucide-react'
import Card from '../components/Card'
import Dropdown from '../components/Dropdown'
import Table, { type Column } from '../components/Table'
import StatusBadge from '../components/StatusBadge'
import Input from '../components/Input'
import Select from '../components/Select'
import Button from '../components/Button'
import type { Sensor, SensorType, SensorStatus } from '../types'

const typeOptions = [
  { value: 'All Types', label: 'All Types' },
  { value: 'Velocity', label: 'Velocity' },
  { value: 'Temperature', label: 'Temperature' },
  { value: 'Vibration', label: 'Vibration' },
  { value: 'Brake', label: 'Brake' },
  { value: 'Energy', label: 'Energy' },
  { value: 'Doors', label: 'Doors' },
  { value: 'GPS', label: 'GPS' },
  { value: 'Rails', label: 'Rails' },
]

const statusOptions = [
  { value: 'All Status', label: 'All Status' },
  { value: 'Online', label: 'Online' },
  { value: 'Warning', label: 'Warning' },
  { value: 'Offline', label: 'Offline' },
]

const sensorTypeSelectOptions = typeOptions
  .filter((o) => o.value !== 'All Types')
  .map((o) => ({ value: o.value, label: o.label }))

const sensorStatusSelectOptions = statusOptions
  .filter((o) => o.value !== 'All Status')
  .map((o) => ({ value: o.value, label: o.label }))

export default function Sensors() {
  const [sensors, setSensors] = useState<Sensor[]>([])
  const [search, setSearch] = useState('')
  const [typeFilter, setTypeFilter] = useState('All Types')
  const [statusFilter, setStatusFilter] = useState('All Status')
  const [showAddForm, setShowAddForm] = useState(false)

  const [name, setName] = useState('')
  const [sensorId, setSensorId] = useState('')
  const [type, setType] = useState<SensorType>('Velocity')
  const [location, setLocation] = useState('')
  const [threshold, setThreshold] = useState('')
  const [status, setStatus] = useState<SensorStatus>('Online')

  const filtered = useMemo(() => {
    return sensors.filter((s) => {
      const matchesSearch =
        search.trim() === '' ||
        s.name.toLowerCase().includes(search.toLowerCase()) ||
        s.sensorId.toLowerCase().includes(search.toLowerCase())
      const matchesType = typeFilter === 'All Types' || s.type === typeFilter
      const matchesStatus = statusFilter === 'All Status' || s.status === statusFilter
      return matchesSearch && matchesType && matchesStatus
    })
  }, [sensors, search, typeFilter, statusFilter])

  const resetForm = () => {
    setName('')
    setSensorId('')
    setType('Velocity')
    setLocation('')
    setThreshold('')
    setStatus('Online')
  }

  const handleCreate = (e: FormEvent) => {
    e.preventDefault()
    if (!name || !sensorId || !location || !threshold) return
    const newSensor: Sensor = {
      sensorId,
      name,
      type,
      location,
      status,
      value: '--',
      threshold,
    }
    setSensors((prev) => [newSensor, ...prev])
    resetForm()
    setShowAddForm(false)
  }

  const handleDelete = (id: string) => {
    setSensors((prev) => prev.filter((s) => s.sensorId !== id))
  }

  const columns: Column<Sensor>[] = [
    { key: 'sensorId', header: 'Sensor ID', render: (r) => r.sensorId },
    { key: 'name', header: 'Name', render: (r) => r.name },
    { key: 'type', header: 'Type', render: (r) => r.type },
    { key: 'location', header: 'Location', render: (r) => r.location },
    { key: 'status', header: 'Status', render: (r) => <StatusBadge status={r.status} /> },
    { key: 'value', header: 'Value', render: (r) => r.value },
    { key: 'threshold', header: 'Threshold', render: (r) => r.threshold },
    {
      key: 'actions',
      header: 'Actions',
      render: (r) => (
        <div className="flex items-center gap-2">
          <button className="rounded-md p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gomobi-dark" aria-label="Edit sensor">
            <Pencil size={14} />
          </button>
          <button
            onClick={() => handleDelete(r.sensorId)}
            className="rounded-md p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-500"
            aria-label="Delete sensor"
          >
            <Trash2 size={14} />
          </button>
        </div>
      ),
    },
  ]

  return (
    <div>
      <div className="flex flex-wrap items-start justify-between gap-3">
        <div>
          <h1 className="font-heading text-2xl font-bold text-gomobi-dark">Sensor Management</h1>
          <p className="mt-1 text-sm text-gray-500 font-body">
            Monitor and configure railway sensors
          </p>
        </div>
        <Button onClick={() => setShowAddForm((v) => !v)} className="flex items-center gap-1.5">
          <Plus size={15} />
          Add Sensor
        </Button>
      </div>

      {showAddForm && (
        <Card className="mt-5">
          <div className="mb-4 flex items-center justify-between">
            <p className="font-heading text-base font-semibold text-gomobi-dark">Add New Sensor</p>
            <button
              onClick={() => setShowAddForm(false)}
              className="text-xs text-gray-400 hover:text-gomobi-dark font-body"
            >
              Cancel
            </button>
          </div>
          <form onSubmit={handleCreate} className="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <Input
              label="Name"
              placeholder="Temperature Sensor A1"
              value={name}
              onChange={(e) => setName(e.target.value)}
            />
            <Input
              label="Sensor ID"
              placeholder="SENS-001"
              value={sensorId}
              onChange={(e) => setSensorId(e.target.value)}
            />
            <Select
              label="Type"
              options={sensorTypeSelectOptions}
              value={type}
              onChange={(e) => setType(e.target.value as SensorType)}
            />
            <Input
              label="Location"
              placeholder="Train A - Car 3"
              value={location}
              onChange={(e) => setLocation(e.target.value)}
            />
            <Input
              label="Threshold"
              placeholder="75.5"
              value={threshold}
              onChange={(e) => setThreshold(e.target.value)}
            />
            <Select
              label="Status"
              options={sensorStatusSelectOptions}
              value={status}
              onChange={(e) => setStatus(e.target.value as SensorStatus)}
            />
            <div className="flex items-center gap-3 sm:col-span-2">
              <Button type="submit" fullWidth>
                Create Sensor
              </Button>
              <Button type="button" variant="secondary" onClick={() => setShowAddForm(false)}>
                Cancel
              </Button>
            </div>
          </form>
        </Card>
      )}

      <div className="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center">
        <div className="relative flex-1">
          <Search size={16} className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
          <input
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Search by name or ID..."
            className="w-full rounded-lg border border-gray-200 py-2.5 pl-9 pr-3 text-sm font-body text-gomobi-dark outline-none placeholder:text-gray-400 focus:border-gomobi-purple focus:ring-1 focus:ring-gomobi-purple"
          />
        </div>
        <Dropdown value={typeFilter} options={typeOptions} onChange={setTypeFilter} className="sm:w-40" />
        <Dropdown value={statusFilter} options={statusOptions} onChange={setStatusFilter} className="sm:w-40" />
      </div>

      <div className="mt-4">
        <Table columns={columns} data={filtered} rowKey={(r) => r.sensorId} emptyMessage="No sensors found" />
      </div>
    </div>
  )
}
