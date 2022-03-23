<?php

namespace App\Entity;

use App\Repository\HotelsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HotelsRepository::class)]
class Hotels
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $name;

    #[ORM\Column(type: 'string', length: 50)]
    private $address;

    #[ORM\Column(type: 'string', length: 50)]
    private $city;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\ManyToMany(targetEntity: Users::class, inversedBy: 'hotels')]
    private $users;

    #[ORM\OneToMany(mappedBy: 'hotels', targetEntity: ReservationRooms::class)]
    private $reservation_rooms;

    #[ORM\OneToMany(mappedBy: 'hotels', targetEntity: HotelRooms::class)]
    private $hotel_rooms;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->reservation_rooms = new ArrayCollection();
        $this->hotel_rooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, ReservationRooms>
     */
    public function getReservationRooms(): Collection
    {
        return $this->reservation_rooms;
    }

    public function addReservationRoom(ReservationRooms $reservationRoom): self
    {
        if (!$this->reservation_rooms->contains($reservationRoom)) {
            $this->reservation_rooms[] = $reservationRoom;
            $reservationRoom->setHotels($this);
        }

        return $this;
    }

    public function removeReservationRoom(ReservationRooms $reservationRoom): self
    {
        if ($this->reservation_rooms->removeElement($reservationRoom)) {
            // set the owning side to null (unless already changed)
            if ($reservationRoom->getHotels() === $this) {
                $reservationRoom->setHotels(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HotelRooms>
     */
    public function getHotelRooms(): Collection
    {
        return $this->hotel_rooms;
    }

    public function addHotelRoom(HotelRooms $hotelRoom): self
    {
        if (!$this->hotel_rooms->contains($hotelRoom)) {
            $this->hotel_rooms[] = $hotelRoom;
            $hotelRoom->setHotels($this);
        }

        return $this;
    }

    public function removeHotelRoom(HotelRooms $hotelRoom): self
    {
        if ($this->hotel_rooms->removeElement($hotelRoom)) {
            // set the owning side to null (unless already changed)
            if ($hotelRoom->getHotels() === $this) {
                $hotelRoom->setHotels(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
