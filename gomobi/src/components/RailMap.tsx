export default function RailMap() {
  return (
    <div className="rounded-xl border border-gray-100 bg-white p-4 shadow-card">
      <div className="flex items-center justify-between">
        <p className="text-sm font-semibold text-gomobi-dark font-body">Sector Map Alpha</p>
        <div className="flex items-center gap-4 text-xs font-body text-gray-500">
          <span className="flex items-center gap-1.5">
            <span className="h-2 w-2 rounded-full bg-emerald-500" />
            Online
          </span>
          <span className="flex items-center gap-1.5">
            <span className="h-2 w-2 rounded-full bg-amber-500" />
            Warning
          </span>
          <span className="flex items-center gap-1.5">
            <span className="h-2 w-2 rounded-full bg-red-500" />
            Offline
          </span>
        </div>
      </div>

      <div className="relative mt-3 h-64 w-full overflow-hidden rounded-lg bg-white sm:h-72">
        <svg
          viewBox="0 0 600 300"
          className="absolute inset-0 h-full w-full"
          preserveAspectRatio="none"
        >
          <defs>
            <pattern id="grid" width="30" height="30" patternUnits="userSpaceOnUse">
              <path d="M 30 0 L 0 0 0 30" fill="none" stroke="#f1f2f5" strokeWidth="1" />
            </pattern>
          </defs>
          <rect width="600" height="300" fill="url(#grid)" />

          <path
            d="M -10 60 C 150 60, 200 200, 350 180 S 550 60, 620 90"
            fill="none"
            stroke="#e5e7f0"
            strokeWidth="2.5"
          />
          <path
            d="M -10 240 C 130 260, 220 100, 340 120 S 500 260, 620 230"
            fill="none"
            stroke="#e5e7f0"
            strokeWidth="2.5"
          />
          <path
            d="M -10 150 C 180 90, 300 210, 620 150"
            fill="none"
            stroke="#e5e7f0"
            strokeWidth="2.5"
          />
        </svg>
      </div>
    </div>
  )
}
