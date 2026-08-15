export type TrainStatus = 'Online' | 'Warning' | 'Offline'

export interface SensorReading {
  temperature: string
  humidity: string
  brake: string
  status: 'CHK' | 'OK'
}

export interface Train {
  id: string
  line: string
  status: TrainStatus
  nextStation: string
  eta: string
  currentSpeed: string
  destination: string
  reading: SensorReading
}

export type SensorType =
  | 'Velocity'
  | 'Temperature'
  | 'Vibration'
  | 'Brake'
  | 'Energy'
  | 'Doors'
  | 'GPS'
  | 'Rails'

export type SensorStatus = 'Online' | 'Warning' | 'Offline'

export interface Sensor {
  sensorId: string
  name: string
  type: SensorType
  location: string
  status: SensorStatus
  value: string
  threshold: string
}

export interface User {
  name: string
  email: string
  role: 'viewer' | 'admin'
  status: 'active' | 'inactive'
  memberSince: string
  lastLogin: string
}

export interface Alert {
  id: string
  message: string
  severity: 'info' | 'warning' | 'critical'
  timestamp: string
}
