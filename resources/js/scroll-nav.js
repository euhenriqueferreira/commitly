export function scrollNav() {
    return {
        bottomHidden: false,
 
        lastScrollY: 0,
        ticking: false,
 
        SCROLL_THRESHOLD: 8,
        SCROLL_TOP_OFFSET: 10,
 
        onScroll(event) {
            const currentY = event.target.scrollTop
 
            if (!this.ticking) {
                window.requestAnimationFrame(() => {
                    this._handleScroll(currentY)
                    this.ticking = false
                })
                this.ticking = true
            }

        },
 
        _handleScroll(currentY) {
            const delta = currentY - this.lastScrollY
 
            if (delta > this.SCROLL_THRESHOLD && currentY > this.SCROLL_TOP_OFFSET) {
                this.bottomHidden = true
            } else if (delta < -this.SCROLL_THRESHOLD) {
                this.bottomHidden = false
            }
            this.lastScrollY = currentY
        },
    }
}