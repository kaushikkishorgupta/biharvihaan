document.addEventListener("DOMContentLoaded", () => {
    // Mobile Touch/Swipe interactions for Marquee
    const marqueeWrappers = document.querySelectorAll('.clients-marquee-wrapper');
    
    marqueeWrappers.forEach(wrapper => {
        let isDown = false;
        let startX;
        let scrollLeft;
        
        // Touch events for mobile swiping
        wrapper.addEventListener('touchstart', (e) => {
            isDown = true;
            startX = e.touches[0].pageX - wrapper.offsetLeft;
            scrollLeft = wrapper.scrollLeft;
            // Pause animation temporarily when swiping
            wrapper.querySelectorAll('.clients-marquee').forEach(el => {
                el.style.animationPlayState = 'paused';
            });
        }, {passive: true});
        
        wrapper.addEventListener('touchend', () => {
            isDown = false;
            // Resume animation
            wrapper.querySelectorAll('.clients-marquee').forEach(el => {
                el.style.animationPlayState = 'running';
            });
        });
        
        wrapper.addEventListener('touchmove', (e) => {
            if (!isDown) return;
            const x = e.touches[0].pageX - wrapper.offsetLeft;
            const walk = (x - startX) * 2; // Scroll speed multiplier
            wrapper.scrollLeft = scrollLeft - walk;
        }, {passive: true});
    });
});
