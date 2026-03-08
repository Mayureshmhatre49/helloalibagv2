$items = @(
    "demo-stay-1", "demo-stay-2", "demo-stay-3", "demo-stay-4", "demo-stay-5",
    "demo-eat-1", "demo-eat-2", "demo-re-1", "demo-events-1", "demo-explore-1", "demo-services-1"
)

foreach ($item in $items) {
    $text = $item.Replace("-", " ").ToUpper()
    $url = "https://placehold.co/800x600/f1f5f9/64748b/png?text=$text"
    $outfile = "c:\xampp\htdocs\helloalibgv2\storage\app\public\listings\$item.jpg"
    Invoke-WebRequest -Uri $url -OutFile $outfile
}
