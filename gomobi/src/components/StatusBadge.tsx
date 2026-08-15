type Status = 'Online' | 'Warning' | 'Offline' | 'Delayed' | 'active' | 'viewer' | 'admin'

const styles: Record<string, string> = {
  Online: 'bg-emerald-50 text-emerald-600',
  Warning: 'bg-amber-50 text-amber-600',
  Offline: 'bg-red-50 text-red-500',
  Delayed: 'bg-amber-50 text-amber-600',
  active: 'bg-emerald-50 text-emerald-600',
  viewer: 'bg-gray-100 text-gray-600',
  admin: 'bg-gomobi-purple/10 text-gomobi-purple',
}

const dotStyles: Record<string, string> = {
  Online: 'bg-emerald-500',
  Warning: 'bg-amber-500',
  Offline: 'bg-red-500',
  Delayed: 'bg-amber-500',
}

export default function StatusBadge({
  status,
  showDot = true,
}: {
  status: Status | string
  showDot?: boolean
}) {
  const style = styles[status] ?? 'bg-gray-100 text-gray-600'
  const dot = dotStyles[status]
  return (
    <span
      className={`inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold font-body ${style}`}
    >
      {showDot && dot && <span className={`h-1.5 w-1.5 rounded-full ${dot}`} />}
      {status}
    </span>
  )
}
